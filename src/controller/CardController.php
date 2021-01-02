<?php

namespace Controllers;

use Libraries\Security;
use Models\Card;

class CardController
{
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
        $char,
        $card;

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
        $this->card = Card::getInstance();
    }

    public function Connected()
    {
        return ($this->char->CharacterConnected());
    }

    public function getCharacterCardId($character)
    {
        $val = (!is_null($character) && ($this->char->CharacterExistence($character))) ? $character : $this->session->character;

        return $this->sec->Filter($val, 'Int');
    }


    /**** CARD-PARTS ****/

    /**
     * @fn CharacterPartLifepoint
     * @note Get Character part remained lifepoints
     * @param int $character
     * @param int $part
     * @return array
     */
    public function CharacterPartLifepoint(int $character, int $part): array
    {

        #Parse passed data
        $character = $this->sec->Filter($character, 'Int');
        $part = $this->sec->Filter($part, 'Int');

        #Set damage and total values
        $damage = $this->card->CharacterPartDamage($character, $part);
        $total = $this->card->PartTotalLifePoint($part);

        #Calc remained lifepoint
        $remained = $this->sec->Filter(($total - $damage), 'Int');

        #Return values
        return ['Remained' => $remained, 'Total' => $total];
    }

    /**
     * @fn PartStatus
     * @note Get part status
     * @param $lifepoints
     * @return array
     */
    public function PartStatus($lifepoints): array
    {

        #Parse passed data
        $lifepoints = $this->sec->Filter($lifepoints, 'Int');

        #Get parts list
        $status_list = $this->card->StatusList();

        #Foreach status
        foreach ($status_list as $status) {

            #Get max hp for status
            $max = $this->sec->Filter($status['max_hp'], 'Int');

            #IF life points is low than max for that status
            if ($lifepoints <= $max) {

                #Parse needed data
                $text = $this->sec->Filter($status['text'], 'String');
                $descr = $this->sec->Filter($status['description'], 'String');

                #Return needed data
                return ['Status' => $text, 'Description' => $descr];
            }
        }

    }

    /**
     * @fn PartDamagesList
     * @note Get list of part damages
     * @param int $character
     * @param int $part
     * @return array
     */
    public function PartDamagesList(int $character, int $part): array
    {
        #Parse passed data
        $character = $this->sec->Filter($character, 'Int');
        $part = $this->sec->Filter($part, 'Int');

        #Return list of damages of the part
        return $this->card->CharacterPartDamageList($character, $part);
    }

}