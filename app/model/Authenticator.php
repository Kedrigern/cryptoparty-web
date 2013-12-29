<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant
 */

use \Nette\Security,
	\Nette\Utils\Strings;

/**
 * Users authenticator.
 */
class Authenticator extends \Nette\Object implements Security\IAuthenticator
{
	/** @var \Nette\Database\Connection */
	private $database;
    /**
     * @var \Cryptoparty\UserRepository
     */
    protected $userRep;
    /** @var string salt */
    private static $salt = 'Sul nad zlato a i nad';

    /**
     * @param \Nette\Database\Context $database
     * @param \Cryptoparty\UserRepository $userRep
     */
    public function __construct(\Nette\Database\Context $database, \Cryptoparty\UserRepository $userRep)
	{
		$this->database = $database;
        $this->userRep = $userRep;
	}

	/**
	 * Performs an authentication.
     * @param array credentials
	 * @return \Nette\Security\Identity
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($mail, $password) = $credentials;
		unset($credentials);
		$row = $this->userRep->GetByMail($mail);

		if (!$row) {
			throw new Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		}

		if ( $row->password != trim($this->calculateHash($password))) {
            $this->userRep->login($mail);
			throw new Security\AuthenticationException('The password is incorrect.' , self::INVALID_CREDENTIAL);
		}

		unset($password);
        $this->userRep->login($mail, false);
		return new Security\Identity($row->id, $row->role, $row->toArray());
	}

	/**
	 * Computes salted password hash.
	 * @param  string
	 * @return string
	 */
	public static function calculateHash($password)
	{
		return crypt($password, Authenticator::$salt);
	}

}
