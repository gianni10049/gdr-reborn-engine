<?php

namespace Controllers;

use Libraries\Security;
use Models\Character;

/**
 * @class CharacterController
 * @package Controllers
 * @note Controller for characters
 */
class CharacterController
{
    /**
     * Init vars PUBLIC STATIC
     * @var CharacterController $_instance
     */
    public static
        $_instance;

    /**
     * Init vars PRIVATE
     * @var Character $character
     * @var Security $sec
     */
    private
        $character,
        $sec;

    /**
     * @fn __construct
     * @note CharacterController constructor.
     * @return void
     */
    public function __construct()
    {
        #Init needed classes
        $this->sec = Security::getInstance();
        $this->character = Character::getInstance();
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @return CharacterController
     */
    public static function getInstance(): CharacterController
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
     * @fn createCharacter
     * @note Method for create character
     * @param array $data
     * @return int
     */
    public function createCharacter(array $data):int
    {

    }

    /**
     * @fn getCharacter
     * @note Get character data
     * @return array
     */
    public function getCharacter(): array
    {
        #Return character data
        return $this->character->getCharacter();
    }

    #TODO Da rividere
    /**
     * @fn GetStat
     * @param string $stat
     * @return array|bool
     */
    public function GetStat(string $stat)
    {
        #Filter name of the needed stat
        $skill = $this->sec->Filter($stat);

        return $this->character->GetStat($stat);
    }
}