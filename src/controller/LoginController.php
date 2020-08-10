<?php

namespace Controllers;

use Libraries\Request,
    Libraries\Security,
    Controllers\CharacterController,
    Models\Login,
    Models\Config,
    Models\Account;

/**
 * @class LoginController
 * @package Controllers
 * @note Controller for manage authentication controls
 */
class LoginController
{
    /**
     * Init vars PRIVATE
     * @var Config $config
     * @var Security $security
     * @var Request $request
     * @var Login $login_model
     * @var Account $account
     */
    private
        $config,
        $security,
        $request,
        $login_model,
        $account;

    /**
     * Init vars PUBLIC STATIC
     * @var LoginController $_instance
     */
    public static
        $_instance;

    /**
     * @fn __constructor
     * @note Init needed classes
     * @return void
     */
    private function __construct()
    {
        #Call instances of the needed classes
        $this->security = Security::getInstance();
        $this->request = Request::getInstance();
        $this->login_model = Login::getInstance();
        $this->config = Config::getInstance();
        $this->account = AccountController::getInstance();
        $this->character = CharacterController::getInstance();
    }


    /**
     * @fn getInstance
     * @note Self Instance
     * @return LoginController
     */
    public static function getInstance(): LoginController
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
     * @fn authenticate
     * @note Authenticate user credentials for Login
     * @param string $username
     * @param string $credential
     * @return int
     */
    public function authenticate(string $username, string $credential): int
    {

        #Filter entered data
        $username = $this->security->Filter($username, 'String');
        $credential = $this->security->Filter($credential, 'String');

        #If one of the field are empty or not isset
        if ((!isset($username) || empty($username)) || (!isset($credential) || empty($credential))) {
            return LOGIN_EMPTY_VALUES;
        } #Else if the user have reached the max attempts for login
        else if ($this->login_model->countAttempts($this->request->getIpAddress(),$username) >= $this->config->login_max_attempts) {
            return LOGIN_MAX_ATTEMPTS;
        }

        #Read data of the user from the DB
        $user = $this->account->GetAccountData('Username',$username);

        #If user exist in database
        if (!empty($user)) {
            #Compare credentials for authentication
            if ($this->security->VerifyHash($credential, $user['password'])) {

                #Init new Session
                $session = SessionController::getInstance();

                # Destroy old session for regenerate for limit error
                if($session->SessionExist()){
                    $session->destroy();
                }

                #Insert needed parameters for login
                $session->id = $this->security->Filter($user['id'],'Int');

                #Generate fingerprint and update the account last activity
                $this->account->RegenerateFingerprint($session->id);
                $this->account->CreateLastActive();

                # Set favorite character
                $this->character->LoginFavorite($session->id);

                #Return true
                return LOGIN_SUCCESS;

            } #Else the user credentials are wrong
            else {
                #Create text for log the error in db
                $error = "Authentication Error [Password]";

                #Log the error in the db
                $this->login_model->insertError($error, $this->request->getIPAddress(),$username);

                #Die whit error
                return LOGIN_PASSWORD_ERROR;
            }
        } #Else the user not exist
        else {
            #Create text for log the error in db
            $error = "Authentication Error [Username]";

            #Log the error in the db
            $this->login_model->insertError($error, $this->request->getIPAddress(),$username);

            #Die whit error
            return LOGIN_USERNAME_ERROR;
        }
    }

    /**
     * @fn ManageError
     * @note Methods for manage authentication errors
     * @param int $response
     * @return string
     */
    public function ManageError(int $response):string
    {
        #Init empty html var
        $text = '';
        $type = '';

        #Switch passed response
        switch ($response) {

            case (int)LOGIN_SUCCESS:
                $type = 'success';
                break;

            #Case username error
            case (int)LOGIN_USERNAME_ERROR:
                $text = 'Account inesistente.';
                $type = 'info';
                break;

            #Case password error
            case (int)LOGIN_PASSWORD_ERROR:
                $text = 'Password errata.';
                $type = 'error';
                break;

            #Case max attempts
            case (int)LOGIN_MAX_ATTEMPTS:
                $text = 'Raggiunto numero massimo di tentativi.';
                $type = 'error';
                break;

            #Case empty values
            case (int)LOGIN_EMPTY_VALUES:
                $text = 'Assicurati di aver compilato tutti i campi correttamente.';
                $type = 'info';
                break;
        }

        $json = ['text'=>$text,'type'=>$type];

        #Return composed html
        return json_encode($json);

    }
}
