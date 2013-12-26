<?php namespace App\FrontModule;

use Nette\Application\UI;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends \App\BasePresenter
{
	/**
	 * @var \Cryptoparty\UserRepository @inject
	 */
	public $repository;

    public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.', 'success');
		$this->redirect('in');
	}

    /**
     * Sign-in form factory.
     * @return \Cryptoparty\Form\SignInForm
     */
    protected function createComponentSignInForm()
    {
        return new \Cryptoparty\Form\SignInForm( $this->repository );
    }

    /**
     * Sign-up form factory.
     * @return \Cryptoparty\Form\RegistrationForm
     */
    protected function createComponentSignUpForm()
    {
        return new \Cryptoparty\Form\RegistrationForm( $this->repository );
    }

    /**
     * New password form factory.
     * @return \Cryptoparty\Form\NewPasswdForm
     */
    protected function createComponentSendNewPasswd()
    {
        return new \Cryptoparty\Form\NewPasswdForm( $this->repository );
    }
}
