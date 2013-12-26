<?php namespace App\AdminModule;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 **/

use Nette\Application\UI\Form;
use dflydev\markdown\MarkdownParser;

/**
 * Admin Article Presenter
 */
class ArticlePresenter extends \App\BasePresenter
{
    /**
     * @var \Cryptoparty\ArticleRepository @inject
     */
    public $articleRep;

    /**
     * @var \Cryptoparty\AuthorRepository @inject
     */
    public $authorRep;

	/**
	 * @var \Cryptoparty\TagRepository @inject $tagRep
	 */
	public $tagRep;

    public function renderDefault()
    {
        $this->template->articles = $this->articleRep->findAll()
        ->order('date_published');
    }

    public function renderAdd()
    {
      $this['editForm']->setDefaults(
            array('id' => 0)
        );
    }

    /**
     * @param int $id
     */
    public function renderEdit($id)
    {
        $row = $this->articleRep->get($id);
        $form = $this['editForm'];

	    $form->setDefaults(
            $row
        );
	    $form['tags']->setDefaultValue(
		    $this->articleRep->findTagsToItem($id)
	    );
    }

	/**
	 * @param int $id
	 */
	public function handlePublish($id)
	{
		$this->articleRep->publish($id);
		if($this->isAjax()) {

		} else {
			$this->flashMessage('Publikováno' . $id, 'success');
		}
	}

	/**
	 * @param int $id
	 */
	public function handleUnpublish($id)
	{
		$this->articleRep->unpublish($id);
		if($this->isAjax()) {

		} else {
			$this->flashMessage('Skryto', 'success');
		}
	}

	/**
	 * @return \Cryptoparty\Form\ArticleEditForm
	 */
	protected function createComponentEditForm()
    {
	    return new \Cryptoparty\Form\ArticleEditForm(
		    $this->articleRep,
		    $this->authorRep,
		    $this->tagRep
	    );
    }

	/**
	 * @return ArticleNiftyGrid
	 */
	protected function createComponentArticleGrid()
	{
		return new \AdminModule\ArticleNiftyGrid($this->articleRep, $this->authorRep);
	}
}