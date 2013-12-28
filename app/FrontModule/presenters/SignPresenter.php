<?php namespace App\FrontModule;

use Cryptoparty\Form,
	Nette\Application\UI;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends \App\BasePresenter
{
	/**
	 * @var \Cryptoparty\UserRepository @inject
	 */
	public $repository;

	public function renderIn()
	{
		$this->template->var = boolval($this->repository->isAvailable('ondrej.profant@gmail.com'));
	}

    public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.', 'success');
		$this->redirect('in');
	}

    /**
     * Sign-in form factory.
     * @return Form\SignInForm
     */
    protected function createComponentSignInForm()
    {
        return new Form\SignInForm( $this->repository );
    }

    /**
     * Sign-up form factory.
     * @return Form\RegistrationForm
     */
    protected function createComponentSignUpForm()
    {
        return new Form\RegistrationForm( $this->repository );
    }

    /**
     * New password form factory.
     * @return Form\NewPasswdForm
     */
    protected function createComponentSendNewPasswd()
    {
        return new Form\NewPasswdForm( $this->repository );
    }
}
