<?php

namespace Controllers;

use Libraries\Mailer;
use Database\DB;
use Libraries\Security;
use Models\Account;
use Models\Config;

/**
 * @class CheckAccount
 * @package Controllers
 * @note Controller for account security
 */
class AccountController
{
    /**
     * Init vars PUBLIC STATIC
     * @var AccountController $_instance
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
        $account,
        $mailer,
        $config;

    /**
     * @fn getInstance
     * @note Self Instance
     * @return AccountController
     */
    public static function getInstance(): AccountController
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
        $this->mailer = Mailer::getInstance();
        $this->config = Config::getInstance();
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
        return ( !empty($id) );
    }

    /**
     * @fn GetAccountData
     * @note Function for switch the method for retrieve account data
     * @param string $type
     * @param string $value
     * @return mixed
     */
    public function GetAccountData(string $type, string $value)
    {

        # Switch the type of request
        switch ($type) {

            # Case username read info by username
            case 'Username':
                return $this->account->readByName($value);

            # Case id read info by id
            case 'Id':
                return $this->account->readById($value);

            # Case email read info by email
            case 'Email':
                return $this->account->readByEmail($value);

            default:
                return false;
        }
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
    public function PasswordUpdate(array $post): int
    {
        #Filter passed data
        $user = $this->sec->Filter($post['username'], 'Convert');
        $NewPass = $this->sec->Filter($post['password'], 'Convert');
        $EmailVerification = $this->sec->Filter($post['email'], 'String');

        #If infos match whit username passed
        if ($this->ConfirmData($user, $EmailVerification)) {

            #If password is correct for update
            if ($this->sec->PasswordControl($NewPass)) {

                #Update password
                $this->account->SetPassword($user, $NewPass);

                #Return success response
                return PASS_UPDATE_SUCCESS;

            } #Else password is not correct for update
            else {

                #Return control error
                return PASS_UPDATE_CONTROL_ERROR;
            }

        } #Else infos don't match whit username
        else {

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
    public function EmailUpdate(array $post): int
    {
        #Filter passed data
        $user = $this->sec->Filter($post['username'], 'Convert');
        $pass = $this->sec->Filter($post['password'], 'Convert');
        $oldEmail = $this->sec->Filter($post['old_email'], 'String');
        $newEmail = $this->sec->Filter($post['new_email'], 'String');

        #If infos match whit username passed
        if ($this->account->DataMatch($user, $pass, $oldEmail)) {

            #Update email
            $this->account->SetEmail($user, $newEmail);

            #Return success response
            return EMAIL_UPDATE_SUCCESS;

        } #Else infos don't match whit username
        else {

            #Return data match error
            return EMAIL_UPDATE_MATCH_ERROR;
        }
    }

    /**
     * @fn PasswordRecovery
     * @note Recovery password operation
     * @param string $username
     * @param string $email
     * @return string
     */
    public function PasswordRecovery(string $username, string $email):string
    {
        # Filter insert data
        $username = $this->sec->Filter($username, 'String');
        $email = $this->sec->Filter($email, 'String');

        # If email and username exist
        if ($this->account->EmailExist($email) && $this->account->UsernameExist($username)) {

            #If email and username is of the same account
            if ($this->ConfirmData($username, $email)) {

                #Start new pass empty var
                $newPass = '';

                #While the password is not correct
                while (!$this->sec->PasswordControl($newPass)) {

                    #Generate new password
                    $newPass = $this->RandomPassword(10, 'lower_case,upper_case,numbers,special_symbols');
                }

                #If password is correct
                if ($this->sec->PasswordControl($newPass)) {

                    # Update password in database
                    $update = $this->PasswordUpdate(['username' => $username, 'password' => $newPass, 'email' => $email]);

                    # If update success
                    if ($update === PASS_UPDATE_SUCCESS) {

                        # Create email text
                        $text = "Cambio password avvenuto con successo! 
                    
                            Il tuo account: {$username}
                            La tua password: {$newPass}
                            
                            Buon gioco!
                        ";

                        # Send email whit new password
                        $this->mailer->SendEmail([$email], $this->config->domain_email, 'Cambio password!', $text, [], true);

                        # Set response to success
                        $response = PASSWORD_RECOVERY_SUCCESS;

                    } # Else update is password
                    else {

                        # Set response to update error
                        $response = PASSWORD_RECOVERY_UPDATE_ERROR;
                    }

                } # Else password not correctly created
                else {

                    # Set response to creation error
                    $response = PASSWORD_RECOVERY_CREATION_ERROR;
                }

            } # Else username and email not is of the same account
            else {

                # Set response to confirm error
                $response = PASSWORD_RECOVERY_CONFIRM_ERROR;
            }

        } # Else username or email don't exist
        else {

            # Set response to existence error
            $response = PASSWORD_RECOVERY_EXISTENCE_ERROR;
        }

        # Return managed response
        return $this->PasswordRecoveryError($response);

    }

    /**
     * @fn ConfirmData
     * @note Confirm if data is of the same account
     * @param string $username
     * @param string $email
     * @return bool
     */
    private function ConfirmData(string $username,string $email):bool
    {

        # Get account data from username
        $data = $this->GetAccountData('Username', $username);

        # Get email of the account
        $dbEmail = $this->sec->Filter($data['email'], 'String');

        # Decrypt email
        $decrypted = $this->sec->Decrypt($dbEmail);

        # If is the same return true, else return false
        return ( $email == $decrypted );
    }

    /**
     * @fn RandompPassword
     * @note Generate a random password
     * @param int $length
     * @param string $characters
     * @return string
     */
    public function RandomPassword(int $length, string $characters):string
    {

        # Set initial vars
        $symbols = array();
        $used_symbols = '';
        $pass = '';

        # Set array of the option symbols
        $symbols["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
        $symbols["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $symbols["numbers"] = '1234567890';
        $symbols["special_symbols"] = '!?~@#-_+<>[]{}';

        # Create array of needed values
        $characters = explode(",", $characters); // get characters types to be used for the passsword

        # Foreach type of characters
        foreach ($characters as $key => $value) {

            # Add symbols to used symbols
            $used_symbols .= $symbols[$value]; // build a string with all characters
        }

        # Get max length of the used symbols
        $symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of characters deduct 1

        while (strlen($pass) < $length) {

            $n = rand(0, $symbols_length); // get a random character from the string with all characters

            $pass .= $used_symbols[$n]; // add the character to the password string
        }

        # Return random password
        return $pass;
    }

    /**
     * @fn PasswordRecoveryError
     * @note Manage password recovery error
     * @param int $response
     * @return string
     */
    private function PasswordRecoveryError(int $response):string
    {

        #Init empty html var
        $text = '';
        $type = '';

        #Switch passed response
        switch ($response) {

            #Case success
            case (int)PASSWORD_RECOVERY_SUCCESS:
                $text = 'Password modificata con successo. Controllare l\'email di riferimento con la nuova password.';
                $type = 'success';
                break;

            #Case update error
            case (int)PASSWORD_RECOVERY_UPDATE_ERROR:
                $text = 'Errore nell\'update della password. Contattare lo staff tramite email.';
                $type = 'error';
                break;

            #Case creation error
            case (int)PASSWORD_RECOVERY_CREATION_ERROR:
                $text = 'Errore nella creazione della nuova password. Contatare lo staff tramite email.';
                $type = 'error';
                break;

            #Case confirm error
            case (int)PASSWORD_RECOVERY_CONFIRM_ERROR:
                $text = 'Username ed email non coincidono con lo stesso account.';
                $type = 'warning';
                break;

            #Case existence error
            case (int)PASSWORD_RECOVERY_EXISTENCE_ERROR:
                $text = 'Username o email inesistenti in database.';
                $type = 'warning';
                break;
        }

        $json = ['type'=>$type,'text'=>$text];

        #Return composed html
        return json_encode($json);

    }

    /**
     * @fn UsernameRecovery
     * @note Recovery username operation
     * @param string $email
     * @return string
     */
    public function UsernameRecovery(string $email): string
    {
        # Filter insert data
        $email = $this->sec->Filter($email, 'String');

        # If this email exist
        if($this->account->EmailExist($email)) {

            # Get account data from email
            $data = $this->GetAccountData('Email', $email);

            # Filter obtained username
            $id= $this->sec->Filter($data['id'],'Int');
            $username = $this->sec->Filter($data['username'],'String');

            # If username exist
            if($this->account->UsernameExist($username) && $this->AccountExist($id)){

                # Compose text
                $text= "Richiesta di recupero username.
                
                    L'username collegato a questa email risulta essere: {$username} .
                    
                    Buon gioco!
                ";

                # Send email whit username
                $this->mailer->SendEmail([$email],$this->config->domain_email,'Recupero account',$text,[],true);

                # Set success response
                $response = USERNAME_RECOVERY_SUCCESS;

            } # Else username don't exist
            else{

                # Set username not existence error
                $response = USERNAME_RECOVERY_EXISTENCE_ERROR;
            }

        } # Else email don't exist
        else{

            # Set email not existence error
            $response = USERNAME_RECOVERY_EMAIL_ERROR;
        }

        # Return managed error
        return $this->UsernameRecoveryError($response);

    }

    /**
     * @fn UsernameRecoveryError
     * @note Manage username recovery error
     * @param int $response
     * @return string
     */
    private function UsernameRecoveryError(int $response): string
    {

        #Init empty needed vars
        $text= '';
        $type = '';

        #Switch passed response
        switch ($response) {

            #Case success
            case (int)USERNAME_RECOVERY_SUCCESS:
                $text = 'Username inviato con successo. Controllare l\'email inserita.';
                $type = 'success';
                break;

            #Case update error
            case (int)USERNAME_RECOVERY_EXISTENCE_ERROR:
                $text = 'L\'username di riferimento dell\'email potrebbe essere stato bannato o non esistente.';
                $type = 'warning';
                break;

            #Case creation error
            case (int)USERNAME_RECOVERY_EMAIL_ERROR:
                $text = 'L\'email inserita non risulta associata a nessun account.';
                $type = 'warning';
                break;
        }

        # Create json data
        $json = ['type'=>$type,'text'=>$text];

        # Return json data
        return json_encode($json);

    }

}