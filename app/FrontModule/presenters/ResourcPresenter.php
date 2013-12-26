<?php namespace App\FrontModule;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */

/**
 * Resource presenter
 */
class ResourcePresenter extends \App\BasePresenter
{
	/**
	 * @var \Cryptoparty\ResourceRepository @inject $repository
	 */
	public $repository;

    public function renderDefault()
    {
        $this->template->resources = $this->repository->FindAll();
        $this->template->registerHelper('filetype', callback('\Cryptoparty\Helpers', 'Filetype'));
    }
}