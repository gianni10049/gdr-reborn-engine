<?php

namespace Libraries;

use Models\Config;

/**
 * @class Session
 * @package Libraries
 * @note Class needed for manage sessions
 */
class Session 
{
    /**
     * Init vars PUBLIC STATIC
     * @var Session $_instance
     */
    public static
        $_instance;

    /**
     * Init vars PUBLIC
     * @var array $session_params
     * @var array $session_array
     */
    public
        $session_params,
        $session_array;

    /**
     * Init vars PRIVATE
     * @var Config $config
     * @var Security $sec
     */
    private
        $config,
        $sec;

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Session
     */
    public static function getInstance():Session
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
     * @fn construct
     * @note Session constructor.
     * @return void
     */
    private function __construct()
    {
        #Init security class
        $this->sec = Security::getInstance();

        #Init config for extract session params
        $this->config= Config::getInstance();

        #Extract and set session params
        $this->session_params=
            [
                'cookie_httponly' => $this->config->cookie_httponly,
                'cookie_lifetime' => $this->config->cookie_lifetime
            ];

        #If session is empty
        if(session_status() == PHP_SESSION_NONE) 
        {
            #Start session with extracted session params
            session_start($this->session_params);
        }
    }

    /**
     * @fn __set
     * @note Set magic method
     * @example $session->username = $username;
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, $value)
    {
        #Filter entered value
        $name= $this->sec->Filter($name,'String');

        #Set session value
        $_SESSION[$name] = $value;
    }

    /**
     * @fn __get
     * @note Get magic method
     * @example echo $session->username;
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        #Filter entered value
        $name= $this->sec->Filter($name,'String');

        #If session param exist
        if(isset($_SESSION[$name]))
        {
            #Return session param
            return $_SESSION[$name];
        } #Else not exist
        else{
            #Return false
            return false;
        }
    }

    /**
     * @fn __call
     * @note Call magic method for create session vars dynamically
     * @param string $name
     * @param mixed $value
     * @return Session
     */
    public function __call(string $name,$value)
    {
        #Add the name of the function like array key
        $this->session_array[] = $name;

        #If passed a value
        if(!empty($value)){

            #Create session value
            $this->SaveSession($value[0]);
        }

        #Return class instance
        return $this;
    }

    /**
     * @fn SaveSession
     * @note Save parameter in indicated session var
     * @param $par
     */
    public function SaveSession($par){

        #Get all key indicated
        $values= $this->session_array;

        #Init start string
        $string= '$_SESSION';

        #Foreach key
        foreach ($values as $val){

            #Add the value to the string
            $string .= "['{$val}']";
        }

        #Set parameter like value in indicated session var
        $string .= "= '{$par}';";

        #Eval string like php code
        eval($string);

        #Restart keys array
        $this->array = [];
    }

    /**
     * @fn setSession
     * @note Set a session var
     * @param array $session
     * @return void
     */
    public function setSession(array $session)
    {
        #Foreach session value passed
        foreach($session as $key => $value)
        {
            #If is string, array or integer
            (is_string($value) || is_array($value) || is_int($value))

                #Set session var
                ? ($_SESSION[$key] = $value)

                #Else not set anything
                : false;
        }
    }

    /**
     * @fn regenerate
     * @note Regenerate session id
     * @return void
     */
    public function regenerate()
    {
        #Regerate session id
        session_regenerate_id();
    }
}

