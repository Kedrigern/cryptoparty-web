<?php namespace Cryptoparty\Form;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

use Cryptoparty,
	Nette\Application\UI\Form;

class SignInForm extends Form
{
    /**
     * @var UserRepository
     **/
    private $userRep;

    /**
     * @param Cryptoparty\UserRepository $userRep
     **/
    public function __construct(Cryptoparty\UserRepository $userRep)
    {
        parent::__construct();

        $this->userRep = $userRep;

        $this->addGroup('Přihlášení');

        $this->addText('mail', 'Mail:')
            ->setType('email')
            ->addRule(Form::EMAIL)
            ->setRequired();

        $this->addPassword('password', 'Password:')
            ->setRequired('Please enter your password.');

        $this->addCheckbox('remember', 'Keep me signed in');

        $this->addSubmit('send', 'Sign in')
            ->setAttribute('class', 'btn btn-primary');

        $this->onValidate[] = $this->mailExists;

        $this->onSuccess[] = $this->signInFormSucceeded;
    }

    /**
     * @param \Nette\Application\UI\Form $form
     */
    public function signInFormSucceeded(Form $form)
    {
        $values = $form->getValues();

        if ($values->remember) {
            $this->presenter->getUser()->setExpiration('+ 14 days', FALSE);
        } else {
            $this->presenter->getUser()->setExpiration('+ 20 minutes', TRUE);
        }

        try {
            $this->presenter->getUser()->login($values->mail, $values->password);
        } catch (\Nette\Security\AuthenticationException $e) {
            $form->addError($e->getMessage());
            return;
        }

        $this->presenter->flashMessage("Jste úspěšně přihlášen", 'success');
        $this->presenter->redirect('Homepage:');
    }

    /**
     * @param Form $form
     * @return bool
     * @todo check functionality
     */
    public function mailExists(Form $form)
    {
        $values = $form->getValues();

        if( $this->userRep->isAvailable( $values->mail ) ) {
            return false;
        } else {
            return true;
        }
    }
}