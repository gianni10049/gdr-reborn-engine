<?php

namespace Models;

use Models\Wrapper;

/**
 * @class Login
 * @package Models
 * @note Model for manage login process
 */
class Login extends Wrapper
{

    /**
     * Init vars PUBLIC STATIC
     * @var Login
     */
    public static
        $_instance;

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Login
     */
    public static function getInstance():Login
    {
        #If self-instance not defined
        if (!(self::$_instance instanceof self)) {
            #define it
            self::$_instance = new self();
        }
        #return defined instance
        return self::$_instance;
    }

    /**
     * @fn countAttempts
     * @note Count the max attempts of login from the ip
     * @param string $ip
     * @return int
     */
    public function countAttempts(string $ip): int
    {
        #Control if the use have reached the maximum attempts
        $error = $this->db->Count(
            "login_invalid", "ip = '{$ip}' AND DATE_ADD(timerror, INTERVAL 10 MINUTE) > NOW()"
        );

        #If the user is valid
        return (is_int($error)) ? $error : 0 ;
    }

    /**
     * @fn insertError
     * @note Add the login error to the invalids attempts
     * @param  string $message
     * @param  string $ip
     * @return void
     */
    public function insertError(string $message, string $ip)
    {
        #Insert error in db
        $this->db->Insert(
            "login_invalid","message, ip","'{$message}','{$ip}'"
        );
    }

    /**
     * @fn readByName
     * @note Extract data of the user by his username
     * @param string $username
     * @return mixed
     */
    public function readByName(string $username = null)
    {
        #Return account data
        return $this->db->Select(
            "*","account","username = '{$username}' AND active = 1 LIMIT 1"
        )->Fetch();
    }
}