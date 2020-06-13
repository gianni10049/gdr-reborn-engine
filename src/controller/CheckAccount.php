<?php

namespace Controllers;

use Database\DB,
    Libraries\Security,
    Models\Account;

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
        $id = $this->sec->Filter($this->account->id, 'Int');

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

            #If account exist return true, else return false
            return $this->account->ExistenceControl($account);

        } #Else account is empty or null
        else {

            #Return false
            return false;
        }
    }

    /**
     * @fn RegenerateFingerprint
     * @note Regenerate fingerprint and update in db
     * @param int|null $id
     * @return void
     */
    public function RegenerateFingerprint(int $id = null)
    {
        #If account exist
        if ($this->AccountExist($id)) {

            #Get account id
            $account = (is_null($id)) ? $account = $this->sec->Filter($this->account->id, 'Int') : $account = $this->sec->Filter($id, 'Int');

            #Update fingerprint in db
            $this->account->UpdateFingerprint($account);
        }
    }

    /**
     * @fn CreateLastActive
     * @note Create and update last_active in db
     * @param int|null $id
     * @return void
     */
    public function CreateLastActive(int $id = null)
    {
        #If account exist
        if ($this->AccountExist($id)) {

            #Get account id
            $account = (is_null($id)) ? $account = $this->sec->Filter($this->account->id, 'Int') : $account = $this->sec->Filter($id, 'Int');

            #Update fingerprint in db
            $this->account->UpdateLastActive($account);
        }
    }

    /**
     * @fn PasswordUpdate
     * @note Update password in db
     * @param array $post
     * @return int
     */
    public function PasswordUpdate(array $post):int
    {
        #Filter passed data
        $user = $this->sec->Filter($post['username'], 'Convert');
        $OldPass = $this->sec->Filter($post['old_password'], 'Convert');
        $NewPass = $this->sec->Filter($post['new_password'], 'Convert');
        $EmailVerification = $this->sec->Filter($post['email_verification'], 'Email');

        #If infos match whit username passed
        if ($this->account->DataMatch($user, $OldPass, $EmailVerification)) {

            #If password is correct for update
            if ($this->sec->PasswordControl($NewPass)) {

                #Update password
                $this->account->SetPassword($user,$NewPass);

                #Return success response
                return PASS_UPDATE_SUCCESS;

            } #Else password is not correct for update
            else{

                #Return control error
                return PASS_UPDATE_CONTROL_ERROR;
            }

        } #Else infos don't match whit username
        else{

            #Return data match error
            return PASS_UPDATE_MATCH_ERROR;
        }
    }

    /**
     * @fn EmailUpdate
     * @note Update password in db
     * @param array $post
     * @return int
     */
    public function EmailUpdate(array $post):int
    {
        #Filter passed data
        $user = $this->sec->Filter($post['username'], 'Convert');
        $pass = $this->sec->Filter($post['password'], 'Convert');
        $oldEmail = $this->sec->Filter($post['old_email'], 'Email');
        $newEmail = $this->sec->Filter($post['new_email'], 'Email');

        #If infos match whit username passed
        if ($this->account->DataMatch($user, $pass, $oldEmail)) {

                #Update email
                $this->account->SetEmail($user,$newEmail);

                #Return success response
                return EMAIL_UPDATE_SUCCESS;

        } #Else infos don't match whit username
        else{

            #Return data match error
            return EMAIL_UPDATE_MATCH_ERROR;
        }
    }
}