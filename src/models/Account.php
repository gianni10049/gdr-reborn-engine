<?php

namespace Models;

use Controllers\CheckSession,
    Database\DB,
    Libraries\Security,
    Libraries\Session;

/**
 * @class Account
 * @package Models
 * @
 */
class Account
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

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Account
     */
    public static function getInstance(): Account
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
     * @note Account constructor.
     * @return void
     */
    private function __construct()
    {
        #Init needed classes
        $this->sec = Security::getInstance();
        $this->sessionController = CheckSession::getInstance();
        $this->session = Session::getInstance();
        $this->db = DB::getInstance();

        #Extract data of the account
        $this->RetrieveData();
    }

    /**
     * @fn RetrieveData
     * @note Extract data of the account and save in object $datas
     * @return void
     */
    public function RetrieveData()
    {
        #If session exist
        if ($this->sessionController->SessionExist()) {

            #Get account id
            $account = $this->session->id;

            #Get pdo object
            $db = $this->db;

            #Select data of the account
            $data = $db->Select("*","account","id='{$account}' LIMIT 1");

            #Save account data
            $this->datas = $data[$account];

            #Save id account
            $this->datas['id'] = $account;
        }
    }

    /**
     * @fn __get
     * @note extract account data from $datas var
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        #Filter entered value
        $name = $this->sec->Filter($name, 'String');

        #If data is not extracted
        if (empty($this->datas)) {

            #Extract data
            $this->RetrieveData();
        }

        #If session param exist
        if (isset($this->datas[$name])) {
            #Return session param
            return $this->datas[$name];
        } #Else not exist
        else {
            #Return false
            return false;
        }
    }
}