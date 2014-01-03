<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */

class ResourceRepository extends BaseRepository
{
	use TTaggable;

    /**
     * @param \Nette\Database\Context $conn
     */
    public function __construct(\Nette\Database\Context $conn)
    {
        parent::__construct($conn, 'resource');
    }

	/**
	 * @param $data
	 * @return \Nette\Database\Table\ActiveRow
	 */
	public function insert($data)
    {
        unset($data['id']);
        unset($data['date_modified']);

        return $this->context->table($this->tableName)->insert($data);
    }

	/**
	 * @param int $id
	 * @param array $data
	 * @return int
	 * @throws InvalidArgumentException
	 */
	public function update($id, $data)
    {
        unset($data['id']);
        unset($data['date_modified']);

        $row = $this->context
            ->table($this->tableName)
            ->get($id);
        if( $row == FALSE) {
            throw new InvalidArgumentException;
        }
        return $row->update($data);
    }
}