<?php

namespace Controllers;

use Models\Character,
    Libraries\Security;

class CharacterController
{
    /**
     * Init vars PUBLIC STATIC
     * @var CharacterController $_instance
     */
    public static $_instance;

    /**
     * Init vars 
     */
    protected $character,
            $sec;

    public function __construct(int $id)
    {
        $this->sec = Security::getInstance();
        $id = $this->sec->Filter($id, 'Int');

        $this->character = Character::getInstance($id);
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @return CharacterController
     */
    public static function getInstance(int $id): CharacterController
    {
        #If self-instance not defined
        if (!(self::$_instance instanceof self)) {
            #define it
            self::$_instance = new self($id);
        }
        #return defined instance
        return self::$_instance;
    }

    public function createCharacter(array $data)
    {

    }

    public function getCharacter(): array
    {
        return $this->character->getCharacter();
    }

    /**
     * @fn getSkill
     *
     * @param string $skill
     * @return void
     */
    public function getSkill(string $skill)
    {
        $skill = $this->sec->Filter($skill);

        $this->character->getStat($skill);
    }
}