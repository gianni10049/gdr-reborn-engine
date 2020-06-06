<?php

namespace Core;

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

    /***** DATABASE PARAMETERS *****/
    public $host = 'host';
    public $db = 'db';
    public $pass = 'psw';
    public $user = 'user';
    public $charset = 'utf8mb4_unicode_ci';

    /***** ******/
    public $AllowedMethods = ['GET', 'POST'];
}