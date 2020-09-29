<?php

namespace Models;

use Controllers\SessionController;
use Database\DB;
use Libraries\Security;

/**
 * @class Character
 * @package Models
 * @note Model for character
 */
class Character
{
    /**
     * Init vars PUBLIC STATIC
     * @var Account $_instance
     */
    public static
        $_instance;

    /**
     * Init vars PRIVATE
     * @var DB $db
     * @var Security $sec
     * @var SessionController $session
     */
    private
        $db,
        $sec,
        $session;

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Character
     */
    public static function getInstance(): Character
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
     * @fn __constructor.
     * @note Character constructor.
     * @return void
     */
    private function __construct()
    {
        #Init needed classes
        $this->sec = Security::getInstance();
        $this->session = SessionController::getInstance();
        $this->db = DB::getInstance();
    }

    /**
     * @fn RetrieveData
     * @note Extract data of the characters table and save in object $data
     * @param int $id
     * @return mixed
     */
    public function RetrieveData(int $id)
    {
        #Get account id
        $id = $this->sec->Filter($id, 'Int');

        #Get pdo object
        $db = $this->db;

        #Select data of the character, if account is the same than session
        return $db->Select("*", "characters", "id='{$id}' LIMIT 1")->Fetch();
    }

    /**
     * @fn CharacterExistence
     * @note Control if character exist
     * @param int $character
     * @return bool
     */
    public function CharacterExistence(int $character):bool
    {
        # Count number of account whit that id
        $data= $this->db->Count('characters',"id='{$character}' LIMIT 1");

        # If exist return true, else return false
        return ($data === 1);
    }

    /**
     * @fn getOwner
     * @note Extract owner of the character
     * @param int $character
     * @return int|bool
     */
    public function getOwner($character){

        # Filter passed character id
        $character= $this->sec->Filter($character,'Int');

        # Return owner id of the character
        return $this->db->Select('account','characters',"id='{$character}' LIMIT 1")->Fetch()['account'];
    }

    /**
     * @fn CharactersList
     * @note Extract data of the characters connected whit that account
     * @param int $account
     * @return mixed
     */
    public function CharactersList(int $account)
    {
        # Filter entered account id
        $account = $this->sec->Filter($account,'Int');

        # Return array of characters list
        return $this->db->Select('*','characters',"account='{$account}'")->FetchArray();
    }

    /**
     * @fn CharacterStats
     * @note Extract stats of the selected character
     * @param int $character
     * @return mixed
     */
    public function CharacterStats($character){

        #Filter character id
        $character= $this->sec->Filter($character,'Int');

        #Return array of character stats
        return $this->db->Join(
            'characters_stats',
            'characters_stats.value,stats_list.*',
            'stats_list',
            'stats_list.id = characters_stats.stat',
            "characters_stats.character='{$character}'"
        )->FetchArray();
    }

    /**
     * @fn UpdateLogout
     * @note Update logout in session and in db
     * @param int $account
     * @result void
     */
    public function UpdateLogout(int $account){

        # Set session character var on null
        $this->session->character = NULL;

        # Filter passed data
        $account= $this->sec->Filter($account,'num');

        # Set all character of the account not selected in db
        $this->db->Update('characters','selected=0',"account='{$account}'");
    }

    /**
     * @fn UpdateCharacterLogin
     * @note Set character login in session and in db
     * @param int $character
     * @return void
     */
    public function UpdateCharacterLogin( int $character){

        # Filter passed data
        $character= $this->sec->Filter($character,'Int');
        $account= $this->sec->Filter($this->session->id,'Int');

        # Logout other character
        $this->UpdateLogout($account);

        # Set session character var whit the character id
        $this->session->character = $character;

        # Set character selected in db
        $this->db->Update('characters','selected=1',"id='{$character}'");
    }

    /**
     * @fn LeaveFavorite
     * @note Leave favorite from all the character of the account
     * @result void
     */
    public function LeaveFavorite(){

        # Filter passed data
        $account = $this->sec->Filter($this->session->id,'Int');

        # Set all character of the account not selected in db
        $this->db->Update('characters','favorite=0',"account='{$account}'");
    }

    /**
     * @fn UpdateFavorite
     * @note Set favorite character
     * @param int $account
     * @param int $character
     * @return void
     */
    public function UpdateFavorite(int $character){

        # Filter passed data
        $character= $this->sec->Filter($character,'num');

        # Leave old favorite
        $this->LeaveFavorite();

        # Set new favorite
        $this->db->Update('characters','favorite=1',"id='{$character}'");
    }

    /**
     * @fn getFavorite
     * @note get favorite character of the account
     * @param int $account
     * @return int
     */
    public function getFavorite(int $account)
    {

        # Filter passed data
        $account= $this->sec->Filter($account,'num');

        # Get id of the favorite character for the account
        return $this->db->Select("id",'characters',"account='{$account}' AND favorite = '1'")->Fetch()['id'];
    }

}