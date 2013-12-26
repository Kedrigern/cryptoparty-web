<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant
 */

class AuthorRepository extends BaseRepository
{
    /**
     * @param \Nette\Database\connection $conn
     */
    public function __construct(\Nette\Database\Context $conn)
    {
        parent::__construct($conn, 'author');
    }
    
    /**
     * @param array $data
     */
    public function insert($data)
    {
      $this->getTable()->insert($data);
    }

    public function findPairs()
    {
        return $this->conn
            ->table($this->tableName)
            ->select('id, name')
            ->order('name')
            ->fetchPairs('id', 'name');
    }
}