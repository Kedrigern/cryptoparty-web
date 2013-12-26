<?php

namespace App;

use Nette,
	Model;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	public function startup()
	{
		parent::startup();

		try {
			$this->checkPermissions();
		} catch(\Nette\Application\ForbiddenRequestException $e) {
			$this->flashMessage('Pro přístup nemáte oprávnění.','error');
			$this->redirect(':Front:Homepage:');
		}
	}

	protected function checkPermissions()
	{
		if (!$this->user->isAllowed($this->name, $this->view)) {
			throw new \Nette\Application\ForbiddenRequestException();
		}
	}

	/**
	 * @return Cryptoparty\Components\Footer
	 **/
	protected function createComponentFooter()
	{
		return new Cryptoparty\Components\Footer($this->user);
	}
}
