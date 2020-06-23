<?php

namespace Models;

use Controllers\CheckSession,
    Database\DB,
    Libraries\Security,
    Libraries\Session;

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
     * @var CheckSession $sessionController
     * @var Session $session
     */
    private
        $data,
        $db,
        $sec,
        $sessionController,
        $session;

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Character
     */
    public static function getInstance(): Character
    {
        #If self-instance not defined
        if (!(self::$_instance instanceof self))
        {
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
        $this->sessionController = CheckSession::getInstance();
        $this->session = Session::getInstance();
        $this->db = DB::getInstance();
    }

    /**
     * @fn RetrieveData
     * @note Extract data of the characters table and save in object $data
     * @param int $id
     * @return void
     */
    public function RetrieveData(int $id)
    {
        #If session exist
        if ($this->sessionController->SessionExist()) {

            #Get account id
            $id = $this->sec->Filter($id, 'Int');

            #Get pdo object
            $db = $this->db;

            #Select data of the character, if account is the same than session
            $data = $db->Select("*","characters","id='{$id}' LIMIT 1")->Fetch();

            #Save account data
            $this->data = $data;
        }
    }

    /**
     * @fn getCharacter
     * @note Extract data of character by id
     * @return mixed
     */
    public function getCharacter()
    {
        #Return account data
        return $this->data;
    }

    #TODO Da rividere
    /**
     * @fn GetStat
     * @note Get skills data
     * @param string $stat
     * @return array|bool
     */
    public function GetStat(string $stat)
    {
        return $this->db->Select("*","stats","id_character='{$this->data['id']}' AND stat='{$stat}' LIMIT 1")->Fetch();
    }
}