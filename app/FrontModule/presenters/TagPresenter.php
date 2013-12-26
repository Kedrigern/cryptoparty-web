<?php namespace App\FrontModule;

/**
 * @author Ondřej Profant
 * @package Cryptoparty
 *
 * Tag presenter.
 */
class TagPresenter extends \App\BasePresenter
{
    /**
     * @var \Cryptoparty\TagRepository @inject
     */
    public $tags;

    public function renderDefault()
    {
        $this->template->tags = $this->tags->FindAll();
        $this->template->newest = $this->tags->findNewest();
        $this->template->mostUsed = $this->tags->findMostUsed();
    }

    /**
     * @param int tag id
     */
    public function renderView($id)
    {
        $tag = $this->tags->get($id);
        if( $tag === FALSE ) {
            $this->setView('notFound');
        }
        $this->template->tag = $tag;
        $this->template->tags = $this->tags->FindAll();
        $this->template->articles = $this->tags->findItemsToTag($id, 'article');
	    $this->template->resources = $this->tags->findItemsToTag($id, 'resource');
    }
}
