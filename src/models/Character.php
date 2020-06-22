<?php

namespace Models;

use Controllers\CheckSession,
    Database\DB,
    Libraries\Security,
    Libraries\Session;

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
     * @var array $datas
     * @var DB $db
     * @var Security $sec
     * @var CheckSession $sessionController
     * @var Session $session
     */
    private
        $datas,
        $db,
        $sec,
        $sessionController,
        $session;

    public function __construct(int $id)
    {
        #Init needed classes
        $this->sec = Security::getInstance();
        $this->sessionController = CheckSession::getInstance();
        $this->session = Session::getInstance();
        $this->db = DB::getInstance();

        #Extract data of the account
        $this->RetrieveData($id);
    }

    /**
     * @fn RetrieveData
     * @note Extract data of the characters table and save in object $datas
     * @return void
     */
    public function RetrieveData(int $id)
    {
        #If session exist
        if ($this->sessionController->SessionExist()) {

            #Get account id
            $account = $this->session->id;
            $id = $this->sec->Filter($id, 'Int');

            #Get pdo object
            $db = $this->db;

            #Select data of the account //NB field ID_ACCOUNT per agganciare l'account al personaggio
            $data = $db->Select("*","characters","id_account='{$account}' AND id='{$id}' LIMIT 1")->Fetch();

            #Save account data
            $this->datas = $data;

            #Save id account
            $this->datas['id'] = $id;
        }
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Account
     */
    public static function getInstance(int $id): Character
    {
        #If self-instance not defined
        if (!(self::$_instance instanceof self)) 
        {
            #define it
            self::$_instance = new self($id);
        }
        #return defined instance
        return self::$_instance;
    }

    /**
     * @fn getCharacter
     * @note Extract data of character by id
     * @return mixed
     */
    public function getCharacter()
    {
        #Return account data
        return $this->datas;
    }

    //EX. table
    /**
     * skill stat bonus id_character
     * acrobazia 3 2 21
     */
    public function getSkill(string $skill)
    {
        $data = $db->Select("*","stats","id_character='{$this->datas['id']}' AND stat='{$skill}' LIMIT 1")->Fetch();
    }
}