<?php

#TODO Engine in pausa, concentrarsi prima sulla parte dei personaggi

namespace Engine;

use Engine\Opend6;

/**
 * @class Api
 * @package Engine
 * @note Api for manage game engine
 */
class Engine
{

    /**
     * Init vars PRIVATE
     * @var mixed $rpg
     * @var array $rpgs
     */
    private
        $rpg,
        $rpgs = ['interlock', 'basicrp', 'opend6', 'storyteller', 'cypher', 'gurps'];

    /**
     * Init vars PUBLIC
     * @var array $error
     */
    public
        $error;

    /**
     * Init vars PUBLIC STATIC
     * @var Engine $_instance
     */
    public static
        $_instance;

    /**
     * @fn __construct
     * @note Api construct
     * @param string $qstring
     * @return void
     */
    private function __construct(string $qstring = null)
    {
        if(in_array(strtolower($qstring), $this->rpgs))
        {
            switch($qstring)
            {
                case 'opend6':
                    $this->rpg = new Opend6;
                break;
            }
        }
        else
        {
            $this->error = TRUE;
        }
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Engine
     */
    public static function getInstance(): Engine
    {
        #If self-instance not defined
        if (!(self::$_instance instanceof self)) {
            #define it
            self::$_instance = new self();
        }
        #return defined instance
        return self::$_instance;
    }
}