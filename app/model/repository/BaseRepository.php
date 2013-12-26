<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */

class BaseRepository extends \Nette\Object implements IBaseRepository
{
    /**
     * @var \Nette\Database\Context
     */
    protected $conn;
    /**
     * @var string
     */
    protected $tableName;
    /**
     * @var string canonize name in table (title, nickname, name etc.)
     */
    protected $canonizeName = 'name';

    /**
     * @param \Nette\Database\Context $conn
     * @param string $tableName
     * @param string $canonizeName
     */
    public function __construct( \Nette\Database\Context $conn, $tableName, $canonizeName = 'name' )
    {
        $this->conn = $conn;
        $this->tableName = $tableName;
        $this->canonizeName = $canonizeName;
    }

	/**
	 * @inheritdoc
	 */
	public function getCanonizeName()
	{
		return $this->canonizeName;
	}

    /**
     * @inheritdoc
     **/
    public function getTableName()
    {
        return $this->tableName;
    }

	/**
	 * @inheritdoc
	 **/
	public function getTable()
	{
		return $this->conn->table($this->getTableName());
	}

	/**
	 * @inheritdoc
	 **/
	public function getConnection()
	{
		return $this->conn;
	}

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        return $this->conn
            ->table($this->tableName)
            ->select('*')
            ->get($id);
    }

    /**
     * @inheritdoc
     */
    public function find($name)
    {
        return $this->conn
            ->table($this->tableName)
            ->select('*')
            ->where($this->canonizeName.' = ?', $name)
            ->fetch();
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param string|null $order
     * @param string|null $where
     * @return \Nette\Database\Table\Selection
     */
    public function findAll($offset = 0, $limit = 100, $order = null, $where = null)
    {
        $base = $this->conn
            ->table($this->tableName)
            ->select('*')
            ->limit($limit, $offset);
        if( ! is_null($where) ) {
            $base->where($where);
        }
        if( ! is_null($order) ) {
            $base->order($order);
        }
        return $base;
    }

    /**
     * @param string $condition
     * @param array $parameters
     * @return \Nette\Database\Table\Selection
     */
    public function findBy($condition, $parameters)
    {
        return $this->conn
            ->table($this->tableName)
            ->select('*')
            ->where( $condition, $parameters );
    }

	/**
	 * {@inheritdoc}
	 */
	public function count()
	{
		return $this->getTable()
			->select('*')
			->count('*');
	}
}