<?php

#Need to leave "\Example" on hosting
namespace Core\Example;

class Config
{
    public static $_instance;

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public

        /***** DATABASE CONNECTION PARAMETER *****/
        $host = 'host',
        $db = 'db',
        $pass = 'pass',
        $user = 'user',
        $charset = 'utf8mb4_unicode_ci',

        /***** ******/
        $AllowedMethods = ['GET', 'POST'],

        /***** SESSION *****/
        $session_params = ['cookie_httponly' => 1, 'cookie_lifetime' => 0],
        $session_timeout = 360,

        /**** LOGIN ****/
        $LoginMaxAttempt = 10,

        /**** COOKIES ****/
        $cookiExpire = (86400 * 30),
        $cookiePath = '/',
        $cookieDomain = '',
        $cookieSecure = false,
        $cookieHttpOnly = true,

        /**** EMAIL ****/
        $PassMin= 8,
        $PassMax= 16;
}