<?php namespace AdminModule;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

/**
 * Tag grid
 **/
class TagNiftyGrid extends \NiftyGrid\Grid
{
	/**
	 * @var \Cryptoparty\TagRepository
	 */
	protected $tagRep;

	/**
	 * @param \Cryptoparty\TagRepository $tagRep
	 */
	public function __construct(\Cryptoparty\TagRepository $tagRep)
	{
		parent::__construct();
		$this->tagRep = $tagRep;
	}

	/**
	 * @param \Nette\Application\UI\PresenterComponent
	 **/
	protected function configure($presenter)
	{
		$source = new \NiftyGrid\DataSource\NDataSource($this->tagRep->findAll());
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

		$this->addColumn('description', 'Popis')
			->setDateEditable()
			->setDateFilter();
	}

	protected function configureButtons()
	{
		$self = $this;

		$this->addButton(\NiftyGrid\Grid::ROW_FORM, "Rychlá editace")
			->setClass("btn btn-default fast-edit")
			->setIcon('glyphicon glyphicon-pencil')
			->setLabel('Editovat');

		$this->setRowFormCallback(function($values){
			$this->presenter->flashMessage("Řádek aktualizován " . var_dump($values), 'success');}
		);
	}
}