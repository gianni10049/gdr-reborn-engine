<?php

namespace Controllers;

use Libraries\Security,
    Models\Account;

class Signin
{
    /**
     * Init vars PRIVATE
     * @var Security $security
     * @var Account $account
     */
    private
        $security,
        $account;

    /**
     * @fn __constructor
     * @note Init needed classes
     * @return void
     */
    private function __construct()
    {
        #Call instances of the needed classes
        $this->security = Security::getInstance();
        $this->account = CheckAccount::getInstance();
    }

    /**
     * @fn signin
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $conf_pass
     * @return bool
     */
    public function signin(string $username =  null, string $email = null, string $password = null, string $conf_pass = null): bool
    {
        #username filter string
        $username = $this->security->Filter($username);
        #is set username || if exist
        if(($username == null) || ($this->account->UsernameExist($username) == false)) return false;
        #verify email field || if exist
        if(($this->security->setEmail($email) == false) || ($this->account->EmailExist($email) == false)) return false;
        #password security
        if($this->security->PasswordControl($password) == false) return false;
        #password match
        if($this->security->PasswordMatch($password, $conf_pass) == false) return false;

        #new account meth. 
        if($this->account->NewAccount($username, $email, $password)) return true;

        return false;
    }

}