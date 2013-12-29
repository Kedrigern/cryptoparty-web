<?php namespace App\AdminModule;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */

class HomePresenter extends \App\BasePresenter
{
    /**
     * @var \Nette\Database\Context @inject
     */
    public $conn;

    public function renderDefault()
    {
		$dsn = explode('=',$this->conn->getConnection()->dsn);

	    if(isset($dsn[2])) {
		    $dbname = $dsn[2];

	        $tables = $this->conn->query("SHOW FULL TABLES FROM $dbname")->fetchPairs();
	        $result = array();
	        foreach($tables as $name => $type) {
	            $result[$name] = $this->conn->query("SHOW COLUMNS FROM $name")->fetchAll();
	        }
			$this->template->db_info = $result;
		} else {
			$this->template->db_info = array();
		}

        $this->template->authors = $this->conn->table('author')->select('id')->count('id');
	    $this->template->articles = $this->conn->table('article')->select('id')->count('id');
	    $this->template->tags = $this->conn->table('tag')->select('id')->count('id');
	    $this->template->resources = $this->conn->table('resource')->select('id')->count('id');

        $this->template->php_version = phpversion();
        $this->template->php_extensions = get_loaded_extensions();
    }
}