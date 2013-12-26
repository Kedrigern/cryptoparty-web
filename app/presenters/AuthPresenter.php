<?php

/**
 * Presenter that is handling all oauth requests and callbacks
 *
 * @author Michal Svec <pan.svec@gmail.com>
 */
class AuthPresenter extends \Nette\Application\UI\Presenter
{
	/** @var NetteOpauth\NetteOpauth */
	protected $opauth;

	  protected function checkPermissions()
  {}
	
	/**
	 * @param NetteOpauth\NetteOpauth
	 */
	public function injectOpauth(NetteOpauth\NetteOpauth $opauth)
	{
		$this->opauth = $opauth;
	}

	/**
	 * Redirection method to oauth provider
	 *
	 * @param string|NULL $strategy strategy used depends on selected provider - 'fake' for localhost testing
	 */
	public function actionAuth($strategy = NULL)
	{
		$this->opauth->auth($strategy);
		$this->terminate();
	}

	/**
	 * @param string
	 */
	public function actionCallback($strategy)
	{
		$identity = $this->opauth->callback($strategy);

		$this->context->user->login($identity);
		$this->redirect("Front:Homepage:default");
	}

	/**
	 * Basic logout action - feel free to use your own in different presenter
	 */
	public function actionLogout()
	{
		$this->getUser()->logout(TRUE);
		$this->redirect("Front:Homepage:default");
	}
}
