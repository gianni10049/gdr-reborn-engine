<?php

namespace Controllers;

use Database\DB;
use Libraries\Security;
use Models\Account;

/**
 * @class CheckAccount
 * @package Controllers
 * @note Controller for account security
 */
class CheckAccount
{


    /**
     * Init vars PUBLIC STATIC
     * @var CheckAccount $_instance
     */
    public static
        $_instance;

    /**
     * Init vars PRIVATE
     * @var DB $db
     * @var Security $sec
     * @var Account $account
     */
    private
        $db,
        $sec,
        $account;

    /**
     * @fn getInstance
     * @note Self Instance
     * @return CheckAccount
     */
    public static function getInstance(): CheckAccount
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
     * @note CheckAccount constructor.
     * @return void
     */
    private function __construct()
    {
        #Init needed classes
        $this->db = DB::getInstance();
        $this->sec = Security::getInstance();
        $this->account = Account::getInstance();
    }


    #TODO Decidere se integrare nel controllo di AccountConnected anche il controllo di AccountExist;
    /**
     * @fn AccountConnected
     * @note Control if account is connected
     * @return bool
     */
    public function AccountConnected(): bool
    {
        #Extract account id from session
        $id = $this->sec->Filter($this->account->id,'Int');

        #If account id exist return true, else return false
        return (!empty($id)) ? true : false;
    }

    /**
     * @fn AccountExist
     * @note Control if account exist
     * @param int|null $id
     * @return bool
     */
    public function AccountExist(int $id = null): bool
    {
        #If account is not passed get account id from model, else get passed id
        $account = (is_null($id)) ? $account = $this->sec->Filter($this->account->id, 'Int') : $account = $this->sec->Filter($id, 'Int');

        #If account id is not null and not empty
        if (!is_null($account) && !empty($account)) {

            #Count number of account whit that id
            $count = $this->db->Count("account", "id='{$account}'");

            #If account exist return true, else return false
            return ($count === 1) ? true : false;

        } #Else account is empty or null
        else {

            #Return false
            return false;
        }
    }

}