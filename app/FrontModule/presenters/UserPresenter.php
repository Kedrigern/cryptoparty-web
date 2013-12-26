<?php namespace App\FrontModule;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

/**
 * User presenter.
 */
class UserPresenter extends \App\BasePresenter
{
    /**
     * @var \Nette\Database\Context @inject $conn
     */
    public $conn;

    /**
     * @var \Cryptoparty\UserRepository @inject $userRep
     */
    public $userRep;

    public function renderDefault()
    {
        $this->template->userRow = $this->userRep->get($this->user->id);
    }
}