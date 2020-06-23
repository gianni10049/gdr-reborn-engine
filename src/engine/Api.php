<?php

namespace Engine;

use Engine\Opend6;

/**
 * @class Api
 * @package Engine
 * @note Api for manage game engine
 */
class Api
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
     * @var Api $_instance
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
     * @return Api
     */
    public static function getInstance(): Api
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
     * @fn getData
     * @note Get engine data
     * @param string $input
     * @return array
     */
    public function getData(string $input = null): array
    {
        $array = $this->rpg->getData($input);

        return $array;
    }
}