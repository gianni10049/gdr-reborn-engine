<?php

namespace Traits;

use Controllers\AccountController;
use Controllers\CharacterController;
use Libraries\Security;

trait EngineTrait{

    protected
        $character,
        $account,
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
        $this->account = AccountController::getInstance();
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
        return ( ($stat <= $max) && ($stat >= $min) );
    }


}