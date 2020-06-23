<?php

namespace Engine;

use Controllers\CharacterController,
    Libraries\Security,
    Engine\EngineI;

class Opend6 extends EngineI
{
    /**
     * @fn LaunchDice
     * @note Control values before dice roll
     * @param string|null $stat
     * @return array
     */
    public function LaunchDice(string $stat = null): array
    {
        # Filter and get stat data
        $stat = $this->sec->Filter($stat);
        $stats = $this->character->GetStat($stat);

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
        $this->data['roll'] = $this->RollDice($this->data['increment']) + $this->data['partial_die'];

        # Return datas
        return $this->data;
    }

    /**
     * @fn RollDice
     * @note Role dices
     * @param integer $increment
     * @return integer
     */
    public function RollDice(int $increment): int
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

}