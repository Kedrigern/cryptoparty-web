<?php namespace App\AdminModule;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */

class ResourcePresenter extends \App\BasePresenter
{
    /**
     * @var \Cryptoparty\ResourceRepository @inject
     */
    public $repository;

	/**
	 * @var \Cryptoparty\AuthorRepository @inject
	 */
	public $authorRep;

	/**
	 * @var \Cryptoparty\TagRepository @inject
	 */
	public $tagRep;

	public function getBasePath()
	{
		return rtrim($this->context->httpRequest->url->basePath, '/');
	}

    public function renderDefault()
    {
        $this->template->registerHelper('filetype', callback('\Cryptoparty\Helpers', 'Filetype'));
        $this->template->resources = $this->repository->findAll();
    }

    /**
     * @param int $id
     */
    public function renderEdit($id)
    {
        $resource = $this->repository->get($id);

        if( $resource === FALSE ) {
			$this->redirect('default');
        }

        $this['editForm']->setDefaults(
            $resource
        );
	    $this['editForm']['tags']->setDefaultValue(
		    $this->repository->findTagsToItem($id)
	    );
        $this->template->resource = $resource;
    }

    /**
     * @return \Nette\Application\UI\Form
     */
    protected function createComponentEditForm()
    {
	    return new \Cryptoparty\Form\ResourceEditForm(
		    $this->repository,
	        $this->authorRep,
	        $this->tagRep);
    }

	/**
	 * @return ResourceNiftyGrid
	 */
	protected function createComponentResourceGrid()
	{
		return new \AdminModule\ResourceNiftyGrid($this->repository, $this->authorRep);
	}
}