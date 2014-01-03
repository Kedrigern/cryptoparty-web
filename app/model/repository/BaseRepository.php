<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */

/**
 * Class BaseRepository
 * @package Cryptoparty
 * @method string getCanonizeName()
 * @method string getTableName()
 * @method \Nette\Database\Context getContext()
 */
abstract class BaseRepository extends \Nette\Object
{
    /** @var \Nette\Database\Context */
    protected $context;

    /** @var string */
    protected $tableName;

	/** @var string canonize name in table (title, nickname, name etc.) */
    protected $canonizeName = 'name';

    /**
     * @param \Nette\Database\Context $conn
     * @param string $tableName
     * @param string $canonizeName
     */
    public function __construct( \Nette\Database\Context $conn, $tableName, $canonizeName = 'name' )
    {
        $this->context = $conn;
        $this->tableName = $tableName;
        $this->canonizeName = $canonizeName;
    }

	/**
	 * @return \Nette\Database\Table\Selection
	 **/
	public function getTable()
	{
		return $this->context->table($this->getTableName());
	}

	/**
	 * Returns row by primary key
	 * @param int $id
	 * @return \Nette\Database\Table\ActiveRow
	 */
    public function get($id)
    {
        return $this->context
            ->table($this->tableName)
            ->select('*')
            ->get($id);
    }

	/**
	 * Returns row by canonize name
	 * @param string $name
	 * @return \Nette\Database\Table\ActiveRow
	 */
    public function find($name)
    {
        return $this->context
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
        $base = $this->context
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
        return $this->context
            ->table($this->tableName)
            ->select('*')
            ->where( $condition, $parameters );
    }

	/**
	 * Return number of active/visible items in repo
	 * @return int
	 */
	public function count()
	{
		return $this->getTable()
			->select('*')
			->count('*');
	}
}