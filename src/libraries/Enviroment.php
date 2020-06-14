<?php

namespace Libraries;

use Dotenv\Dotenv,
    Libraries\Security;

/**
 * @class Enviroment
 * @package Libraries
 * @note Class for use Dotenv composer package
 */
class Enviroment
{
    /**
     * Init vars PUBLIC STATIC
     * @var Enviroment $_instance
     */
    public static
        $_instance;

    /**
     * Init vars PRIVATE
     * @var Security $sec
     * @var Dotenv $dot
     */
    private
        $sec,
        $dot;

    /**
     * @fn __construct
     * @note Request constructor.
     * @return void
     */
    private function __construct()
    {
        #Init needed classes
        $this->sec = Security::getInstance();
        $this->dot = Dotenv::createImmutable(ROOT);
        $this->dot->load();
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Enviroment
     */
    public static function getInstance(): Enviroment
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
     * @fn __get
     * @note Get enviroment values
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        #If isset, return value, else return false
        return (isset($_ENV[$name])) ? $this->sec->Filter($_ENV[$name]) : false;
    }


}