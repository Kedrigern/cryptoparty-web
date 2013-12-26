<?php namespace App\Cryptoparty\Components;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */

class Footer extends \Nette\Application\UI\Control
{
	/**
	 * @var \Nette\Security\User
	 */
	protected $user;

	/**
	 * @param \Nette\Security\User $user
	 **/
	public function __construct($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/footer.latte');
        $this->template->user = $this->user;
        $this->template->render();
    }
}