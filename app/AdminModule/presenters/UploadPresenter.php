<?php namespace App\AdminModule;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */

use \Cryptoparty\UploadManager;

/**
 * Upload presenter
 */
class UploadPresenter extends \App\BasePresenter
{
    /**
     * @var \Nette\Database\Context @inject $conn
     */
    public $conn;

    /**
     * @var \Cryptoparty\UploadManager
     */
    private $uploadManager;

    /**
     * @var array
     */
    private $errors;

    public function startup()
    {
        parent::startup();
        $this->errors = array();
        try {
            $this->uploadManager = new UploadManager(
                $this->conn,
                $this->user->id,
                UploadManager::GENERAL
            );
        } catch(\Exception $e) {
            array_push($this->errors, $e);
            $this->setView('error');
        }
    }


    public function renderDefault()
    {
        $this->template->uploads = $this->uploadManager->findAll();
    }

    public function renderError()
    {
        $this->template->errors = $this->errors;
    }

    /**
     * @return \Cryptoparty\Components\Admin\Upload
     */
    public function createComponentUpload()
    {
        return new \Cryptoparty\Components\Admin\Upload( $this->uploadManager );
    }
}
