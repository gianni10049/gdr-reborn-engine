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
     * @fn CharacterExistence
     * @note Call model existence control of the character
     * @param int $character
     * @return bool
     */
    public function CharacterExistence($character):bool
    {
        # Filter passed character id
        $character = $this->sec->Filter($character,'Int');

        # Return response existence
        return $this->character->CharacterExistence($character);
    }

    /**
     * @fn CharacterProperty
     * @note Control if account is owner of the character
     * @param int $character
     * @return bool
     */
    public function CharacterProperty($character):bool
    {
        # Filter needed vars
        $character= $this->sec->Filter($character,'Int');
        $account= $this->sec->Filter($this->session->id,'Int');

        # Get and Filter owner id
        $owner= $this->sec->Filter($this->character->getOwner($character),'Int');

        # Control if owner and account is the same
        return $owner === $account;
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
        if ($this->character->CharacterExistence($id)) {
            return $this->character->RetrieveData($id);
        } else {
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
        if ($this->account->AccountExist($account)) {

            #Return characters list
            return $this->character->CharactersList($account);
        } else {
            return false;
        }
    }

    /**
     * @fn getCharacterStats
     * @note Get character stats
     * @param int $character
     * @return array|bool
     **/
    public function getCharacterStats($character)
    {
        # Filter passed character id
        $character = $this->sec->Filter($character, 'Int');

        # If character exist
        if ($this->character->CharacterExistence($character)) {

            #Extract characters stats
            return $this->character->CharacterStats($character);
        } else {
            return false;
        }
    }

    /**
     * @fn ChangeCharacter
     * @note Change active character
     * @param int $character
     * @return string
     */
    public function ChangeCharacter(int $character):string
    {
        # Filter character id and get account id
        $character = $this->sec->Filter($character,'Int');

        # If mine account and character account is the same
        if($this->CharacterProperty($character)){

            # Login in new character
            $this->character->UpdateCharacterLogin($character);

            # Set success response
            $response = ['type'=>'success','text'=>'Personaggio correttamente collegato'];

        } # Else accounts don't are the sames
        else{

            # Set account error response
            $response = ['type'=>'error','text'=>"Personaggio non di proprietà dell'account."];
        }

        # Return json response
        return json_encode($response);

    }

    /**
     * @fn SetFavorite
     * @note Set favorite character
     * @param int $character
     * @return string
     */
    public function SetFavorite(int $character):string
    {
        # Filter character id and get account id
        $character = $this->sec->Filter($character,'Int');

        # If mine account and character account is the same
        if($this->CharacterProperty($character)){
            if($this->CharacterExistence($character)) {

                # Set new Favorite
                $this->character->UpdateFavorite($character);

                # Set success response
                $response = ['type' => 'success', 'text' => 'Personaggio impostato come preferito'];
            }
            else{

                # Set account error response
                $response = ['type'=>'error','text'=>"Personaggio non esistente."];
            }

        } # Else accounts don't are the sames
        else{

            # Set account error response
            $response = ['type'=>'error','text'=>"Personaggio non di proprietà dell'account."];
        }

        # Return json response
        return json_encode($response);

    }

    /**
     * @fn LeaveFavorite
     * @note Leave favorites character
     * @return string
     */
    public function LeaveFavorite(){

        # Leave favorite character
        $this->character->LeaveFavorite();

        # Set success response
        $response = ['type' => 'success', 'text' => 'Preferiti disattivati'];

        # Return json response
        return json_encode($response);
    }

    /**
     * @fn LeaveFavorite
     * @note Leave favorites character
     * @param int $account
     */
    public function LoginFavorite(int $account):void
    {
        # Filter passed data
        $account= $this->sec->Filter($account,'num');

        # Get id of the favorite character
        $favorite = $this->character->getFavorite($account);

        # If favorite is set
        if(!is_null($favorite)) {

            # Control if the character is of the property of the account
            if ($this->CharacterProperty($favorite)) {

                # Set favorite character like active character
                $this->session->character = $favorite;
            }
        } # Else is not set
        else{

            # Set character null
            $this->session->character= NULL;
        }

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