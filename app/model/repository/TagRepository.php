<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */

class TagRepository extends Taggable
{
    /**
     * @var int $limit
     */
    protected $limit;

    /** @param \Nette\Database\Connection  */
    public function __construct( \Nette\Database\Context $conn )
    {
      parent::__construct($conn, 'tag');
      $this->limit = 100;
    }
    
    /**
     * @param array $data
     */
    public function insert($data)
    {
      $this->getTable()->insert($data);
    }

    /**
      * @param int $tagId
      * @param string $itemName
      * @return \Nette\Database\Table\Selection
      */
    public function findItemsToTag($tagId, $itemName)
    {
	    return $this->getConnection()
		    ->table($itemName)
		    ->select($itemName . '.*')
		    ->where( ':' . $this->getTagMapTablePrefix() . $itemName . '.' . 'tag_id = ?', $tagId );
    }

    /**
     * Return 10 tags which is most used
     * @return \Nette\Database\Table\Selection
     */
    public function findMostUsed()
    {
       return $this->conn
			->table( $this->tableName )
			->select( '(COUNT(:tag_rel_article.tag_id) + COUNT(:tag_rel_resource.tag_id)) AS count')
			->select( 'tag.*' )
			->having('count > 1')
			->order( 'count DESC' )
			->limit(10);
    }


    /**
     * @return \Nette\Database\Table\Selection
     */
    public function findNewest()
    {
        return $this->conn
            ->table($this->tableName)
            ->select('*')
            ->order('created DESC')
            ->limit(10);
    }

	/**
	 * @return array|string[]
	 */
	public function findPairs()
	{
		return $this->conn
			->table($this->tableName)
			->order('name')
			->fetchPairs('id','name');
	}
}