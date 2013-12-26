<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant
 */

class UploadManager extends \Nette\Object
{
    const GENERAL = FALSE;
    const USER = TRUE;

    /**
     * @var string
     */
    protected $baseUploadPath = './uploads';

    /**
     * @var string
     */
    protected $tableName = 'upload';

    /**
     * @var string
     */
    protected $uploadDir;

    /**
     * @var \Nette\Database\Table\ActiveRow
     */
    protected $user;

    /**
     * @var \Nette\Database\Connection
     */
    protected $con;

    protected $where;

    /**
     * @param \Nette\Database\Context $con
     * @param int $id
     * @param bool
     * @throws \Nette\InvalidArgumentException
     * @throws \Nette\IOException
     */
    public function __construct(\Nette\Database\Context $con, $uid, $where = UploadManager::USER)
    {
        $this->con = $con;
        $this->user = $this->con->table('user')->select('*')->get($uid);
        $this->where = $where;

        if ($this->user === false) {
            throw new \Nette\InvalidArgumentException();
        }

        switch( $this->where ) {
            case UploadManager::USER:
                $this->uploadDir = $this->baseUploadPath . '/users/' . $this->user->id . '/';

                if( ! is_dir( $this->uploadDir ) ) {
                    if( ! mkdir( $this->uploadDir ) ) {
                        throw new \Nette\IOException('Nelze vytvořit složku');
                    }
                }
                break;
            case UploadManager::GENERAL:
                $this->uploadDir = $this->baseUploadPath . '/general/';
            break;
            default:
                throw new \Nette\InvalidArgumentException('Third parametr of UploadManager constructor is invalid');
        }

        if (!is_writable($this->uploadDir)) {
            throw new \Nette\IOException('Upload path ($this->uploadDir) is not writable');
        }
    }


    /**
     * @param \Nette\ArrayHash $values
     * @throws \Nette\IOException
     */
    public function upload(\Nette\ArrayHash $values)
    {
        $vals2 = $this->uploadFile($values['path']);

        $this->con->table($this->tableName)->insert(
            array(
                'name'        => $values->name,
                'description' => $values->description,
                'fileName'    => $vals2['fileName'],
                'size'        => $vals2['size'],
                'fileMime'    => $vals2['fileMime'],
                'created'     => new \DateTime,
                'user_id'     => $this->user->id,
                'owner'       => $this->where ? 'user' : 'general'
            )
        );
    }


    /**
     * @param int $id
     */
    public function delete($id)
    {
        $record = $this->get($id);

        if ($record == false) {
            return;
        }

        $this->deleteFile($record->fileName);

        $record->delete();
    }

    /**
     * @param int $id
     * @return \Nette\Database\Table\ActiveRow
     * @throws \Nette\InvalidArgumentException
     */
    public function get($id)
    {
        if( ! is_int(intval($id)) ) {
            throw new \Nette\InvalidArgumentException();
        }
        return $this->con
            ->table($this->tableName)
            ->select('*')
            ->get($id);
    }

    /**
     * @return \Nette\Database\Table\Selection
     */
    public function findAll()
    {
        $base = $this->con
            ->table($this->tableName)
            ->select('*');
        switch($this->where) {
            case UploadManager::GENERAL:
                return $base
                    ->where('owner = ?', 'general');
            case UploadManager::USER:
                return $base
                    ->where('owner = ?', 'user')
                    ->where('user_id = ?', $this->user->id);
        }
    }

    /**
     * @param $data
     */
    public function update($data)
    {
        $record = $this->get(  intval( $data['id'] ));

        if (isset($data['path'])) { // upate of file
            $this->deleteFile($record->fileName);
            $vals = $this->uploadFile($data['path']);
            $data['fileName'] = $vals['fileName'];
            $data['size'] = $vals['size'];
        }
        $data['user_id'] = $this->user->id;

        unset($data['id']);
        unset($data['modified']);
        unset($data['created']);
        unset($data['path']);
        unset($data['owner']);
        unset($data['user_id']);

        $record->update($data);
    }

    /**
     * Delete on file from FS (not for DB)
     * @param string $path, only name, not full or relative path
     * @throws \Nette\InvalidArgumentException
     */
    private function deleteFile($fileName)
    {
        if(
            preg_match('#.*\.\..*#', $fileName) or
            preg_match('#^\.#', $fileName)
            ) throw new \Nette\InvalidArgumentException('Pokoušíte se smazat soubor v podezřelé cestě');

        if (file_exists($this->uploadDir . $fileName)) {
            unlink($this->uploadDir . $fileName);
        }
    }

    /**
     * Upload/save on file to proper directory under proper name,
     * Return array of values close link with file for save to DB.
     * @param \Nette\Http\FileUpload $file
     * @return array
     * @throws \Nette\IOException
     */
    private function uploadFile(\Nette\Http\FileUpload $file)
    {
        if ($file->isOk()) {
            $originName = $file->getSanitizedName();

            $names = $this->findName($originName);

            $file->move($names['fullPath']);

            return array(
                'fileName' => $names['name'],
                'size'     => $file->getSize(),
                'fileMime' => $file->getContentType()
            );
        } else {
            throw new \Nette\IOException();
        }
    }

    /**
     * find in directory unused name
     * @param $originName
     * @return array
     */
    private function findName($originName)
    {
        $name = $originName;
        $fullPath = $this->uploadDir . $originName;

        $i = 1;
        while (file_exists($fullPath)) {
            $name = $i . '-' . $originName;
            $fullPath = $this->uploadDir . $name;
        }

        return array(
            'fullPath' => $fullPath,
            'name'     => $name
        );
    }

    /**
     * Check integrity between DB and files in FS for actual instance (only one directory)
     * @throws FilesIntegrityException
     */
    public function checkIntegrity()
    {
        $lostFile = array();

        $files = array();

        foreach (new \DirectoryIterator($this->uploadDir) as $fileInfo) {
            if ($fileInfo->isDot()) continue;
            $files[$fileInfo->getFilename()] = $fileInfo->getFilename();
        }

        $data = $this->con->table($this->tableName)->select('*');
        foreach ($data as $d) {
            if (in_array($d['hash'], $files)) {
                unset($files[$d['hash']]);
            } else {
                //chybí nám file na disku
                array_push($lostFile, $d['hash']);
            }
        }

        // co zbude v $files je v DB navíc
        if (!empty($files) and !empty($lostFile)) {
            $mes = array(
                'lostFiles'      => $lostFile,
                'redundantFiles' => $files
            );
            throw new FilesIntegrityException($mes);
        }
    }
}

class FilesIntegrityException extends \ErrorException
{
    public $files;

    public function __construct($files)
    {
        parent::__construct('Inconsitency between files and db records');
        $this->files = $files;
    }
}