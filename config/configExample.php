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
        $charset = 'utf8mb4_unicode_ci';

}