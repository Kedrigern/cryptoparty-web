<?php namespace App\Cryptoparty\Components;
/**
 * @author Ondřej Profant
 * @package Cryptoparty
 */

use Nette\Security\User;

class Footer extends \Nette\Application\UI\Control
{
	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @param User $user
	 **/
	public function __construct(User $user)
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