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
     * @param \Cryptoparty\UserRepository $rep
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

		BootstrapRenderer::set($this);
    }

    /**
     * @param \Nette\Application\UI\Form $form
     */
    public function RegistrationSucceeded(Form $form)
    {
		if(!$this->isAvailable($form) ) {
			$form['mail']->addError('Tento mail je již použitý, nechte si zaslat nové heslo.');
			return;
		}

	    $values = $form->getValues();

	    if(\Nette\Utils\Strings::lower(trim($values->antispam)) == 'bob') {
            $this->userRep->registration($values->mail, $values->password);
	        $this->presenter->flashMessage("Registrace proběhla úspěšně", 'success');
		} else {
		    $form['antispam']->addError('Špatná odpověď. Jedná se o klasickou kryptografickou dvojici...');
		    return;
		}
    }

    /**
     * @param \Nette\Application\UI\Form $form
     * @return bool
     **/
    public function isAvailable(\Nette\Application\UI\Form $form)
    {
        $values = $form->getValues();

	    return $this->userRep->isAvailable( $values->mail );
    }
}