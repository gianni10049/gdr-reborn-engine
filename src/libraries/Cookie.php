<?php

namespace Libraries;

use Core\Config;

/**
 * @class Cookie
 * @package Libraries
 * @note Class for manage cookies
 */
class Cookie
{
    /**
     * @var float|int
     */

    /**
     * Init vars PRIVATE
     * @var Config $config
     */
    private
        $config;

    /**
     * Init Vars PROTECTED
     * @var int $expire
     * @var string $path
     * @var string $domain
     * @var bool $domain
     * @var bool $httponly
     */
    protected
        $expire,
        $path,
        $domain,
        $secure,
        $httpOnly;

    /**
     * Init Vars PUBLIC STATIC
     * @var Cookie $_instance
     */
    public static
        $_instance;


    /**
     * @fn __construct
     * @note Cookie constructor.
     * @return void
     */
    public function __construct()
    {
        #Init Config class
        $this->config= Config::getInstance();

        #Set config values
        $this->expire = $this->config->cookiExpire;
        $this->path = $this->config->cookiePath;
        $this->domain = $this->config->cookieDomain;
        $this->secure = $this->config->cookieSecure;
        $this->httpOnly = $this->config->cookieHttpOnly;
    }


    /**
     * @fn getInstance
     * @note Self Instance
     * @return Cookie
     */
    public static function getInstance():Cookie
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
     * @fn SetCookie
     * @note Cookie magic method set
     * @example $cookie->username = $username;
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, $value)
    {
        setcookie($name, $value, time() + $this->expire, $this->path, $this->domain, $this->secure, $this->httponly);
    }

    /**
     * @fn GetCookie
     * @note Cookie magic method get
     * @example echo $cookie->username
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        #If cookie exist return ir or return false
        return (isset($_COOKIE[$name])) ? $_COOKIE[$name] : false;
    }

    /**
     * @fn destroyCookie
     * @note Destroy a cookie
     * @example $cookie->destroyCookie($username)
     * @param string $name
     */
    public function destroyCookie(string $name)
    {
        unset($_COOKIE[$name]);
    }

}