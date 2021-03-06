<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant
 */

class UserRepository extends BaseRepository
{
    /** @param \Nette\Database\Context $conn  */
    public function __construct( \Nette\Database\Context $conn )
    {
        parent::__construct($conn, 'user');
    }

    /**
     * @param string $mail
     * @return bool|mixed|IRow
     */
    public function GetByMail($mail)
    {
        return $this->context
            ->table($this->tableName)
            ->select('*')
            ->where('mail = ?', $mail)
            ->fetch();
    }

    /**
     * @param string $mail
     * @param bool
     */
    public function login($mail, $failed = true)
    {
        $row = $this->context
            ->table($this->tableName)
            ->select('*')
            ->where('mail = ?', $mail)
            ->fetch();

        $count = $failed ? ($row->failed_sign_in +1) : 0;

        $row->update(
            array(
                'failed_sign_in' => $count
            ));
    }

    /**
     * @param string $mail
     * @return bool
     */
    public function isAvailable($mail)
    {
        $i = $this->context
            ->table($this->tableName)
            ->select('id')
            ->where('mail = ?', $mail)
            ->count();
        if( $i > 0 ) return false;
        return true;
    }

    /**
     * @param string $mail
     * @param string $password
     * @return bool|int|IRow
     */
    public function registration($mail, $password)
    {
        return $this->context
            ->table($this->tableName)
            ->insert( array(
                'mail' => $mail,
                'password' => Authenticator::calculateHash($password),
                'created' => time()
        ));
    }

    /**
     * @param string $mail
     * @param string $newPasswdHash
     * @return int
     */
    public function newPasswd($mail, $newPasswdHash)
    {
        return $this->context
            ->table( $this->tableName )
            ->where('mail = ?', $mail)
            ->update(array(
                'password' => $newPasswdHash
        ));
    }
}