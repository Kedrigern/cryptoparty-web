<?php namespace AdminModule;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

/**
 * Author grid
 **/
class AuthorNiftyGrid extends \NiftyGrid\Grid
{
	/**
	 * @var \Cryptoparty\AuthorRepository
	 */
	protected $authorRep;

	/**
	 * @param \Cryptoparty\AuthorRepository $authorRep
	 */
	public function __construct(\Cryptoparty\AuthorRepository $authorRep)
	{
		parent::__construct();
		$this->authorRep = $authorRep;
	}

	/**
	 * @param \Nette\Application\UI\PresenterComponent
	 **/
	protected function configure($presenter)
	{
		$source = new \NiftyGrid\DataSource\NDataSource($this->authorRep->findAll());
		$this->setDataSource($source);
		$this->setDefaultOrder("name DESC");

		$this->configureColumns();

		$this->configureButtons();

	}

	protected function configureColumns()
	{
		$this->addColumn('name', 'Jméno')
			->setDateEditable()
			->setDateFilter();
	}

	protected function configureButtons()
	{
		$self = $this;

		$this->addButton(\NiftyGrid\Grid::ROW_FORM, "Rychlá editace")
			->setClass("fast-edit")
			->setIcon('icon-pencil')
			->setLabel('Editovat');

		$this->setRowFormCallback(function($values){

				$this->presenter->flashMessage("Řádek aktualizován " . var_dump($values), 'success');}
		);
	}
}