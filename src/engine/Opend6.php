<?php

namespace Engine;

use Engine\Engine,
    Controllers\CharacterController,
    Libraries\Security;

class Opend6 implements Engine
{
    protected $character,
        $data = [],
        $sec;

    /**
     * costruttore
     *
     * @param integer $id
     */
    public function __construct(int $id)
    {
        $this->sec = Security::getInstance();
        $this->character = CharacterController::getInstance($id);
        $this->sec = Security::getInstance();
    }

    /**
     * getData
     *
     * @param string $skill
     * @return array
     */
    public function getData(string $skill): array
    {
        $skill = $this->sec->Filter($skill);

        $stats = $this->character->getStat($skill);

        //increment & partial die
        $this->data['increment'] = $this->sec->Filter($stats['increment'], 'Int');
        $this->data['partial_die'] = $this->sec->Filter($stats['partial_die'], 'Int');

        if(($this->validateInterval($this->data['increment'], 1, 8) == false) or ($this->validateInterval($this->data['partial_die'], 0, 2) == false))
        {
            $this->data['error'] = 'The Increment field is more than 8 or less than 1 or the Partial Die field is more than 2 or less than 0';

            return $this->data;
        }
        
        $this->data['roll'] = $this->getRoll($this->data['increment']) + $this->data['partial_die'];

        return $this->data;
    }

    /**
     * getRoll
     *
     * @param integer $increment
     * @return integer
     */
    protected function getRoll(int $increment): int
    {
        $roll = [];

        for($i=0; $i < $increment; $i++)
        {
            $roll[$i] = rand(1, 6);
        }

        return array_sum($roll);
    } 

    /**
     * validateInterval
     *
     * @param integer $stat
     * @param integer $min
     * @param integer $max
     * @return boolean
     */
    protected function validateInterval(int $stat, int $min, int $max): bool
    {
        if(($stat <= $max) && ($stat >= $min))
        {
            return true;
        }

        return false;
    }

}