<?php

namespace Engine;

use Engine\Interlock;
use Engine\Basicrp;
use Engine\Opend6;
use Engine\Storyteller;
use Enine\Cypher;
use Engine\Gurps;

class Api
{
    protected $rpg;
    protected $rpgs = ['interlock', 'basicrp', 'opend6', 'storyteller', 'cypher', 'gurps'];
    public $error = FALSE;

    /**
     * instance of RPG obj.
     *
     * @param string $qstring
     */
    public function __construct(string $qstring = null)
    {
        if(in_array(strtolower($qstring), $this->rpgs))
        {
            switch($qstring)
            {
                case 'basicrp':
                    $this->rpg = new Basicrp;
                break;

                case 'interlock':
                    $this->rpg = new Interlock;
                break;

                case 'opend6':
                    $this->rpg = new Opend6;
                break;

                case 'storyteller':
                    $this->rpg = new Storyteller;
                break;

                case 'cypher':
                    $this->rpg = new Cypher;
                break;

                case 'gurps':
                    $this->rpg = new Gurps;
                break;
            }
        }
        else
        {
            $this->error = TRUE;
        }
    }

    /**
     * getData
     *
     * @param array $input
     * @return array
     */
    public function getData(array $input = null): array
    {
        $array = $this->rpg->getData($input);

        return $array;
    }
}