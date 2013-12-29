<?php namespace AdminModule;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

use \Nette\Utils\Html;

/**
 * Resource grid
 **/
class ResourceNiftyGrid extends \NiftyGrid\Grid
{
	/**
	 * @var \Cryptoparty\ResourceRepository
	 */
	protected $resourceRep;

	/**
	 * @var \Cryptoparty\AuthorRepository
	 */
	protected $authorRep;

	/**
	 * @param \Cryptoparty\ResourceRepository $resRep
	 * @param \Cryptoparty\AuthorRepository $authorRep
	 */
	public function __construct(\Cryptoparty\ResourceRepository $resRep, \Cryptoparty\AuthorRepository $authorRep)
	{
		parent::__construct();
		$this->resourceRep = $resRep;
		$this->authorRep = $authorRep;
	}

	/**
	 * @param \Nette\Application\UI\PresenterComponent
	 **/
	protected function configure($presenter)
	{
		$source = new \NiftyGrid\DataSource\NDataSource($this->resourceRep->getTable());
		$this->setDataSource($source);
		$this->setDefaultOrder("created DESC");

		$this->configureColumns();

		$this->configureButtons();

		$this->configureActions();
	}

	protected function configureColumns()
	{
		$that = $this;
	
		$this->addColumn('name', 'Název')
			->setDateEditable()
			->setTextFilter()
			->setAutocomplete(6);

		$authors = $this->authorRep->findPairs();

		$this->addColumn('author_id', 'Autor')
			->setSelectEditable($authors)
			->setSelectFilter($authors)
			->setRenderer(function($row) {
			if(isset($row->author->name))
				return $row->author->name;
			else
				return '';
		});

		$this->addColumn('description', 'Popis', '250px', 65)
			->setTextFilter()
			->setDateEditable();

		$this->addColumn('link_bin', 'Binary')
			->setDateEditable()
			->setRenderer(function($row) use ($that) {
				$path = $that->presenter->basePath . '/images/filetypes/'.$row->filetype_bin .'.png';
				return Html::el('a')
					->href($row->link_bin)->add(
						Html::el('img')
							->addAttributes(array(
								'src' => $path,
								'class' => 'icon')
						)
				);
		});
		$this->addColumn('link_src', 'Src')
			->setDateEditable()
			->setRenderer(function($row) use ($that)  {
				$path = $that->presenter->basePath . '/images/filetypes/'.$row->filetype_src .'.png';
				return Html::el('a')
					->href($row->link_src)->add(
					Html::el('img')
						->addAttributes(array(
							'src' => $path,
							'class' => 'icon')
					)
				);
		});
		$this->addColumn('language', 'Jazyk');

		$this->addColumn('created', 'Publikováno')
			->setRenderer(function($row) {
			$date = new \DateTime($row->created);
			return $date->format('d. M y');
		});

		$this->addColumn('modified', 'Změněno')
			->setRenderer(function($row) {
			$date = new \DateTime($row->modified);
			return $date->format('d. M y');
		});

		$this->addColumn('id', 'Tagy')
			->setRenderer(function($row) use ($that) {
			$s = '';
			foreach( $row->related('tag_rel_resource') as $tag) {
				$s .= $tag->tag->name . ' ';
			};
			return $s;
		});
	}

	protected function configureButtons()
	{
		$self = $this;

		/*
		$this->addButton(\NiftyGrid\Grid::ROW_FORM, "Rychlá editace")
			->setClass("fast-edit")
			->setIcon('icon-pencil');
		*/

		$this->addButton('edit','edit')
			->setLink( function ($row) use ($self) {return $self->presenter->link('edit',$row->id);})
			->setLabel('Editovat')
			->setIcon('glyphicon glyphicon-pencil')
			->setText('Edit')
			->setClass('btn btn-default');

		$this->setRowFormCallback(function($values){

				$this->presenter->flashMessage("Řádek aktualizován " . var_dump($values), 'success');}
		);
	}

	protected function configureActions()
	{
	}
}