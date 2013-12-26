<?php namespace Cryptoparty;

interface IBaseRepository
{
	/**
	 * @return \Nette\Database\Connection
	 **/
	public function getConnection();

	/**
	 * @return string
	 */
	public function getTableName();

	/**
	 * @return \Nette\Database\Table\Selection
	 **/
	public function getTable();

	/**
	 * Return base human readable column name, lite name, title,
	 *
	 * @return string
	 */
	public function getCanonizeName();

	/**
	 * Returns row by primary key
	 *
	 * @param int $id
	 * @return \Nette\Database\Table\ActiveRow
	 */
    public function get($id);

	/**
	 * Returns row by canonize name
	 *
	 * @param string $name
	 * @return \Nette\Database\Table\ActiveRow
	 */
    public function find($name);

	/**
	 * Return number of active/visible items in repo
	 *
	 * @return int
	 */
	public function count();
}

/**
 * It is not possible to define extension methods and interface method at same time
 */
/*
\Nette\Object::extensionMethod('IBaseRepository::getTable', function(\Cryptoparty\IBaseRepository $that) {
	return $that->getConnection()->table($that->getTableName());
});

\Nette\Object::extensionMethod('IBaseRepository::get', function(\Cryptoparty\IBaseRepository $that, $id) {
	return $that->getTable()->get($id);
});

\Nette\Object::extensionMethod('IBaseRepository::find', function(\Cryptoparty\IBaseRepository $that, $name) {
	return $that->getTable()->where($that->getCanonizeName() . ' = ?', $name);
});
*/