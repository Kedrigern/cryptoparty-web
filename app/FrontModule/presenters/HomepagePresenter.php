<?php namespace App\FrontModule;

/**
 * @author Ondřej Profant
 * @package Cryptoparty
 *
 * Homepage presenter.
 */
final class HomepagePresenter extends \App\BasePresenter
{
    /**
     * @var \Cryptoparty\ArticleRepository @inject
     */
    public $articleRep;

	/**
	 * @var \Cryptoparty\TagRepository @inject
	 */
	public $tagRepository;

	/**
	 * @var \Cryptoparty\ResourceRepository @inject
	 */
	public $resourceRepository;

	/**
	 * @var \Cryptoparty\AuthorRepository @inject
	 */
	public $authorRepository;

	public function renderDefault()
	{
		$this->template->articles = $this->articleRep->FindAllArticles();

		$this->template->newest = $this->tagRepository->findNewest();
		$this->template->mostUsed = $this->tagRepository->findMostUsed();

		$this->template->articlesC = $this->articleRep->count();
		$this->template->resourceC = $this->resourceRepository->count();
		$this->template->authorC = $this->authorRepository->count();
	}

	/**
	 * @param int $id
	 **/
	public function renderView($id)
    {
        $this->template->article = $this->articleRep->get($id);
    }
}
