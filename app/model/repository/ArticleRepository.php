<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */

use Nette\InvalidArgumentException;

class ArticleRepository extends Taggable
{
    /**
     * @param \Nette\Database\Context
     */
    public function __construct( \Nette\Database\Context $conn )
    {
        parent::__construct($conn, 'article', 'title');
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param null|string $order
     * @return mixed
     */
    public function FindAllArticles($offset = 0, $limit = 10, $order = null)
    {
        return $this->findAll($offset, $limit, 'date_published DESC', 'visible = 1');
    }

    /**
     * @param int $id
     * @param array $data
     * @return int
     * @throws \Nette\InvalidArgumentException
     */
    public function update($id, $data)
    {
        unset($data['id']);
        unset($data['date_modified']);

        $row = $this->conn
            ->table($this->tableName)
            ->get($id);
        if( $row == FALSE) {
            throw new InvalidArgumentException;
        }
        return $row->update($data);
    }

	/**
	 * @param array $data
	 * @return \Nette\Database\Table\ActiveRow
	 */
	public function insert($data)
    {
        unset($data['id']);
        unset($data['date_modified']);

        return $this->conn->table($this->tableName)->insert($data);
    }

	/**
	 * @param int $id
	 **/
	public function publish($id)
	{
		$this->conn->table($this->tableName)->get($id)->update(array('visible' => TRUE));
	}

	/**
	 * @param int $id
	 **/
	public function unpublish($id)
	{
		$this->conn->table($this->tableName)->get($id)->update(array('visible' => FALSE));
	}

	public function count()
	{
		return $this->getTable()
			->where('visible = ?', true)
			->count('*');
	}

}