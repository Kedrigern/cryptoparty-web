<?php namespace Cryptoparty\Form;
/**
 * @author Ondřej Profant, 2013
 * @package Cryptoparty
 */

use Cryptoparty,
	Nette\Application\UI\Form;

class NewPasswdForm extends Form
{
    /**
     * @var UserRepository
     **/
    private $userRep;

    /**
     * @param Cryptoparty\UserRepository $rep
     **/
    public function __construct( Cryptoparty\UserRepository $rep )
    {
        parent::__construct();

        $this->userRep = $rep;

        $this->addGroup('Zaslání nového hesla');

        $this->addText('mail', 'Mail:')
            ->setType('email')
            ->addRule(Form::EMAIL, 'Email musí být ve správném tvaru')
            ->setRequired('Please enter your mail.');

        $this->addText('antispam', 'Antispam:')
            ->setRequired('Plese enter antispam input')
            ->setOption('description', 'Alice a ?');

        $this->addSubmit('send', 'Zaslat nové heslo')
            ->setAttribute('class', 'btn btn-primary');

        $this->onSuccess[] = $this->newPasswdSucceeded;
    }

	/**
	 * @param Form $form
	 */
	public function newPasswdSucceeded(Form $form)
    {
        $values = $form->getValues();
        if( strtolower($values->antispam) != 'bob' ) {
            sleep(25);  // delay
            $form->addError('Nevyplnili jste správně antispam...');
            return;
        }

        $newPasswd = \Nette\Utils\Strings::random(10, '0-9a-zA-Z$%&#');
        $newPasswdHashed = \Cryptoparty\Authenticator::calculateHash($newPasswd);

        $mail = new \Nette\Mail\Message;
        $mail->setFrom('no-reply@cryptoparty.cz')
            ->addTo( "$values->mail" )
            ->setSubject('cryptoparty.cz: nové heslo')
            ->setBody("Hnusný počasí,\nnové heslo pro tento email je:\n$newPasswd\nNemusím ti snad říkat, že si ho máš okamžitě změnit.")
            ->send();

        $this->userRep->newPasswd($values->mail, $newPasswdHashed);

        $this->flashMessages('Heslo úspěšně změněno.', 'success');
    }
}