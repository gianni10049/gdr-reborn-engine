<?php

namespace Engine;

use Controllers\CharacterController,
    Libraries\Security;

class Opend6 implements Engine
{
    protected $character,
        $data = [],
        $sec;

    /**
     * @fn __construct
     * @note Opend6 constructor
     * @return void
     */
    public function __construct()
    {
        # Init needed classes
        $this->sec = Security::getInstance();
        $this->character = CharacterController::getInstance();
        $this->sec = Security::getInstance();
    }

    /**
     * @fn getData
     * @note Get engine data
     * @param string|null $stat
     * @return array
     */
    public function getData(string $stat = null): array
    {
        # Filter and get stat data
        $stat = $this->sec->Filter($stat);
        $stats = $this->character->getStat($stat);

        # Get increment and partial die
        $this->data['increment'] = $this->sec->Filter($stats['increment'], 'Int');
        $this->data['partial_die'] = $this->sec->Filter($stats['partial_die'], 'Int');

        # If the values is not in the right interval
        if( !$this->validateInterval($this->data['increment'], 1, 8) || !$this->validateInterval($this->data['partial_die'], 0, 2) )
        {
            # Set error
            $this->data['error'] = 'The Increment field is more than 8 or less than 1 or the Partial Die field is more than 2 or less than 0';

            # Return error
            return $this->data;
        }

        # Roll dices
        $this->data['roll'] = $this->getRoll($this->data['increment']) + $this->data['partial_die'];

        # Return datas
        return $this->data;
    }

    #TODO controllare
    /**
     * @fn getRoll
     * @note Role random stats
     * @param integer $increment
     * @return integer
     */
    protected function getRoll(int $increment): int
    {
        # Init empty array
        $roll = [];

        #
        for($i=0; $i < $increment; $i++)
        {
            $roll[$i] = rand(1, 6);
        }

        return array_sum($roll);
    } 

    /**
     * @fn validateInterval
     * @note Validate if the number is in the right interval
     * @param integer $stat
     * @param integer $min
     * @param integer $max
     * @return bool
     */
    protected function validateInterval(int $stat, int $min, int $max): bool
    {
        return (($stat <= $max) && ($stat >= $min)) ? true : false;
    }

}