<?php

namespace Controllers;

use Libraries\Security;
use Models\Character;
use Controllers\AccountController;
use Controllers\SessionController;

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
     * @var AccountController $account
     * @var SessionController $session
     * @var Security $sec
     */
    private
        $character,
        $account,
        $session,
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
        $this->account = AccountController::getInstance();
        $this->session = SessionController::getInstance();
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
     * @fn getCharacter
     * @note Get character data
     * @param int $id
     * @return array|bool
     */
    public function getCharacter(int $id)
    {
        #Return character data
        if($this->character->CharacterExistence($id)) {
            return $this->character->RetrieveData($id);
        }
        else{
            return false;
        }
    }

    /**
     * @fn CharacterList
     * @note Extract an array full of all the data of the characters of the account
     * @return mixed
     */
    public function CharactersList(){

        # Get connected account
        $account = $this->session->id;

        # If account exist
        if($this->account->AccountExist($account)){

            #Return characters list
            return $this->character->CharactersList($account);
        }
    }

    /**
     * @fn ChangeCharacter
     * @param int $id
     * @return string
     */
    public function ChangeCharacter(int $id):string
    {

        # Filter character id and get account id
        $id = $this->sec->Filter($id,'Int');
        $account = $this->sec->Filter($this->session->id,'Int');

        # Get character data for extract account of the requested character
        $data = $this->getCharacter($id);
        $characterAccount = $this->sec->Filter($data['account'],'Int');

        # If mine account and character account is the same
        if($account === $characterAccount){

            # Logout other character
            $this->character->UpdateLogout($account);

            # Login in new character
            $this->character->UpdateCharacterLogin($id);

            # Set success response
            $response = ['type'=>'success','text'=>'Personaggio correttamente collegato'];

        } # Else accounts don't are the sames
        else{

            # Set account error response
            $response = ['type'=>'error','text'=>"Account non di proprietà dell'account."];
        }

        # Return json response
        return json_encode($response);

    }

    /**
     * @fn Logout
     * @note Logout character from the session
     * @return false|string
     */
    public function Logout(){

        # Extract account id
        $account = $this->sec->Filter($this->session->id,'Int');

        # Logout account and save logout in db
        $this->character->UpdateLogout($account);

        # Set success response
        $response = ['type'=>'success','text'=>'Personaggio sloggato correttamente.'];

        # Return json response
        return json_encode($response);
    }

}