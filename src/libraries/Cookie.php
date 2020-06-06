<?php

namespace Libraries;

/** 
 *  class Cookie
 *  
 *  Cookie management        
*/

class Cookie
{
    ///properties eventualmente da Core\Config
    protected static $expire = (86400 * 30);
    protected static $path = "/";
    protected static $domain = "";
    protected static $secure = false;
    protected static $httponly = true;

    /**
     * @fn __set 
     * 
     * Cookie set
     *
     * Ex.: $cookie->username = $username;
     * 
     * @param string $name
     * @param mixed $value
     *
     */
    public function __set(string $name, $value)
    {
        setcookie($name, $value, time() + self::$expire, self::$path, self::$domain, self::$secure, self::$httponly);
    }

    /**
     * @fn __get 
     * 
     * get cookie
     * Ex.: echo $cookie->username;
     * 
     * @param  string $nam
     * @return mixed
     *
     */
    public function __get(string $name)
    {
        if(isset($_COOKIE[$name])) 
        {
            return $_COOKIE[$name];
        }
    }

    /**
     * @fn destroyCookie 
     * 
     * Ex.: $cookie->destroyCookie($username);
     * 
     * @param string $name
     * 
     */
    public function destroyCookie(string $name)
    {
        unset($_COOKIE[$name]);
    }

}