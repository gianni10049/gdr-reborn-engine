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
     * @var array $data
     * @var DB $db
     * @var Security $sec
     * @var SessionController $session
     */
    private
        $data,
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
        $data = $db->Select("*", "characters", "id='{$id}' LIMIT 1")->Fetch();

        #Save account data
        $this->data = $data;

        return $this->data;
    }

    /**
     * @fn CharacterExistence
     * @note Control if character exist
     * @param int $id
     * @return bool
     */
    public function CharacterExistence(int $id):bool
    {
        # Count number of account whit that id
        $data= $this->db->Count('characters',"id='{$id}' LIMIT 1");

        # If exist return true, else return false
        return ($data === 1) ? true : false;
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
     * @fn UpdateCharacterLogin
     * @note Set character login in session and in db
     * @param int $character
     * @return void
     */
    public function UpdateCharacterLogin(int $character){

        # Set session character var whit the character id
        $this->session->character = $character;

        # Set character selected in db
        $this->db->Update('characters','selected=1',"id='{$character}'");
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

        # Set all character of the account not selected in db
        $this->db->Update('characters','selected=0',"account='{$account}'");
    }
}