<?php

namespace Libraries;

class Session 
{
    ///Session params
    //eventualmente da inserire in Core\Config
    public $session_params = 
        [
            'cookie_httponly' => 1, 
            'cookie_lifetime'  => 0 //si chiude alla chiusura del browser
        ];

    /**
     * @fn construct 
     * 
     */
    public function __construct() 
    {
        if(session_status() == PHP_SESSION_NONE) 
        {
            session_start($this->session_params);
        }
    }
   
    /**
     * @fn __set
     *
     * Ex.: $session->username = $username;
     */
    public function __set(string $name, $value)
    {
        $_SESSION[$name] = $value;
    }
   
   
    /**
     * @fn __get 
     * 
     * Ex.: echo $session->username;
     */
    public function __get(string $name)
    {
        if(isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
    }
   
    /**
     * @fn destroy 
     * 
     * Ex.: $session->destroy();
     */
    public function destroy()
    {
        session_destroy();
    }

    /**
     * @fn regenerate
     * 
     * @Ex.: $session->regenerate();
     */
    public function regenerate()
    {
        session_regenerate_id();
    }

    /**
     * setSession
     *
     * @param array $session
     * @return void
     */
    public function setSession(array $session)
    {
        foreach($session as $key => $value)
        {
            if(is_string($value))
            {
                $_SESSION[$key] = $value;
            }
            else if(is_array($value))
            {
                $_SESSION[$key] = $value;
            }
        }
    }
}