<?php

namespace Controllers;

use Libraries\Security;
use Controllers\AccountController;
use Controllers\SessionController;
use Controllers\CharacterController;

class CardController{
    /**
     * Init vars PUBLIC STATIC
     * @var CardController $_instance
     */
    public static
        $_instance;
    /**
     * Init Vars PRIVATE
     * @var SessionController $session
     * @var Security $sec
     * @var AccountController $account
     * @var CharacterController $char
     */
    private
        $session,
        $account,
        $sec,
        $char;

    /**
     * @fn getInstance
     * @note Self Instance
     * @return CardController
     */
    public static function getInstance(): CardController
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
     * @fn __construct
     * @note CardController constructor.
     * @return void
     */
    private function __construct()
    {
        #Init needed classes
        $this->sec = Security::getInstance();
        $this->account = AccountController::getInstance();
        $this->session = SessionController::getInstance();
        $this->char = CharacterController::getInstance();
    }

    public function Connected(){
        return ($this->char->CharacterConnected());
    }

    public function getCharacterCardId($character){
        $val= ( !is_null($character) && ( $this->char->CharacterExistence($character) ) ) ? $character : $this->session->character;

        return $this->sec->Filter($val,'Int');
    }

}