<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant
 */

class UserRepository extends BaseRepository
{
    /** @param \Nette\Database\Connection  */
    public function __construct( \Nette\Database\Context $conn )
    {
        parent::__construct($conn, 'user');
    }

    /**
     * @param string $mail
     */
    public function GetByMail($mail)
    {
        return $this->conn
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
        $row = $this->conn
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
        $i = $this->conn
            ->table($this->tableName)
            ->select('id')
            ->where('mail = ?', $mail)
            ->count();
        if( $i > 0 ) return true;
        return false;
    }

    /**
     * @param string $mail
     * @param string $password
     */
    public function registration($mail, $password)
    {
        return $this->conn
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
        return $this->conn
            ->table( $this->tableName )
            ->where('mail = ?', $mail)
            ->update(array(
                'password' => $newPasswdHash
        ));
    }
}