<?php namespace AdminModule;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

/**
 * Article grid
 **/
class ArticleNiftyGrid extends \NiftyGrid\Grid
{
	/**
	 * @var \Cryptoparty\ArticleRepository
	 */
	protected $articleRep;

	/**
	 * @var \Cryptoparty\AuthorRepository
	 */
	protected $authorRep;

	/**
	 * @param \Cryptoparty\ArticleRepository $articleRep
	 * @param \Cryptoparty\AuthorRepository $authorRep
	 */
	public function __construct(\Cryptoparty\ArticleRepository $articleRep, \Cryptoparty\AuthorRepository $authorRep)
	{
		parent::__construct();
		$this->articleRep = $articleRep;
		$this->authorRep = $authorRep;
	}

	/**
	 * @param \Nette\Application\UI\PresenterComponent
	 **/
	protected function configure($presenter)
	{
		$source = new \NiftyGrid\DataSource\NDataSource($this->articleRep->findAll());
		$this->setDataSource($source);
		$this->setDefaultOrder("date_published DESC");

		$this->configureColumns();

		$this->configureButtons();

		$this->configureActions();
	}

	protected function configureColumns()
	{
		$this->addColumn('title', 'Titulek')
			->setDateEditable()
			->setTextFilter()
			->setAutocomplete(6);

		$authors = $this->authorRep->findPairs();

		$this->addColumn('author_id', 'Autor')
			->setSelectEditable($authors)
			->setSelectFilter($authors)
			->setRenderer(function($row) {
				return $row->author->name;
		});

		$this->addColumn('perex_md', 'Perex', '250px', 65)
			->setDateEditable();

		$this->addColumn('date_published', 'Publikováno')
			//->setDateFilter()
			->setRenderer(function($row) {
				$date = new \DateTime($row->date_published);
				return $date->format('d. M y');
			});

		$this->addColumn('date_modified', 'Změněno')
			//->setDateFilter()
			->setRenderer(function($row) {
				$date = new \DateTime($row->date_modified);
				return $date->format('d. M y');
			});

		$this->addColumn('id', 'Tagy')
			->setRenderer(function($row) {
			$s = '';
			foreach( $row->related('tag_rel_article') as $tag) {
				$s .= $tag->tag->name . ' ';
			};
			return $s;
		});
	}

	protected function configureButtons()
	{
		$self = $this;

		/*$this->addButton(\NiftyGrid\Grid::ROW_FORM, "Rychlá editace")
			->setClass("fast-edit")
			->setIcon('icon-pencil');*/

		$this->addButton('edit','edit')
			->setLink( function ($row) use ($self) {return $self->presenter->link('edit',$row->id);})
			->setLabel('Editovat')
			->setIcon('icon-pencil')
			->setText('Edit');

		$this->addButton("visible", '')
			->setLink( function ($row) use ($self) {return $self->presenter->link( $row['visible'] == "1" ? 'unpublish!' : 'publish!', $row->id);})
			->setLabel(function ($row) {return $row['visible'] == "1" ? "Skrýt" : "Publikovat";})
			->setIcon( function ($row) {return $row['visible'] == "1" ? "icon-ok" : "icon-pause";})
			->setText( function ($row) {return $row['visible'] == "1" ? 'Skrýt' : 'Publikovat'; });

		$this->setRowFormCallback(function($values){

				$this->presenter->flashMessage("Řádek aktualizován " . var_dump($values), 'success');}
		);
	}

	protected function configureActions()
	{
		$self = $this;

		$this->addAction("publish","Publikovat")
			->setCallback(function($ids) use ($self) {
			foreach($ids as $id) {
				$self->presenter->handlePublish($id);
			} });

		$this->addAction("unpublish","Skrýt")
			->setCallback(function($ids) use ($self) {
			foreach($ids as $id) {
				$self->presenter->handleUnpublish($id);
			}
		});
	}
}