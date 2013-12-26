<?php namespace Cryptoparty\Form;
/**
 * @author Ondřej Profant, 2012
 */

use Nette\Application\UI\Form;

class RegistrationForm extends Form
{
    /**
     * @var UserRepository
     **/
    private $userRep;

    /**
     * @param UserRepository $userRep
     **/
	public function __construct( \Cryptoparty\UserRepository $rep )
	{
        parent::__construct();
        $this->userRep = $rep;

        $this->addGroup('Registrace nového uživatele');

        $this->addText('mail', 'Mail:')
            ->setType('email')
            ->addRule(Form::EMAIL)
            ->setRequired('Please enter your mail.');

        $this->addPassword('password', 'Heslo:')
            ->setRequired('Please enter your password.');
        $this->addPassword('password2', 'Ověření hesla:')
            ->setRequired('Please enter your password.')
            ->addRule(Form::MIN_LENGTH, 'Minimální délka hesla je %d znaků', 6)
            ->addRule(Form::EQUAL, 'Hesla se neshodují', $this['password']);
        $this->addText('antispam', 'Antispam:')
            ->setRequired('Plese enter antispam input')
            ->setOption('description', 'Alice a ?');
        $this->addSubmit('send', 'Registrovat')
            ->setAttribute('class', 'btn btn-primary');

        $this->onValidate[] = $this->isAvailable;
        $this->onSuccess[] = $this->RegistrationSucceeded;
    }

    /**
     * @param \Nette\Application\UI\Form $form
     */
    public function RegistrationSucceeded(Form $form)
    {
        $values = $form->getValues();

        $this->userRep->registration($values->mail, $values->password);

        $this->presenter->flashMessage("Registrace proběhla úspěšně", 'success');
    }

    /**
     * @param \Nette\Application\UI\Form $form
     **/
    public function isAvailable(Form $form)
    {
        $values = $form->getValues();

        if( $this->userRep->isAvailable( $values->mail ) ) {
            return true;
        } else {
            return false;
        }
    }
}