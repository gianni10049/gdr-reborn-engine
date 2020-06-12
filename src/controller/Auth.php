<?php

namespace Controllers;

use Libraries\Request,
    Libraries\Security,
    Libraries\Session,
    Models\Login,
    Models\ConfigModel,
    Models\Account;

/**
 * @class Auth
 * @package Controllers
 * @note Controller for manage authentication controls
 */
class Auth
{
    /**
     * Init vars PRIVATE
     * @var ConfigModel $config
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
     * @var Auth $_instance
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
        $this->config = ConfigModel::getInstance();
        $this->account = CheckAccount::getInstance();
    }


    /**
     * @fn getInstance
     * @note Self Instance
     * @return Auth
     */
    public static function getInstance(): Auth
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
     * @return bool
     */
    public function authenticate(string $username, string $credential): bool
    {

        #Filter entered data
        $username = $this->security->Filter($username, 'String');
        $credential = $this->security->Filter($credential, 'String');

        #If one of the field are empty or not isset
        if ((!isset($username) || empty($username)) || (!isset($credential) || empty($credential))) {
            return false;
        } #Else if the user have reached the max attempts for login
        else if ($this->login_model->countAttempts($this->request->getIpAddress()) > $this->config->login_max_attempts) {
            die('Massimo numero di tentativi raggiunto');
        }

        #Read data of the user from the DB
        $user = $this->login_model->readByName($username);

        #If user exist in database
        if (!empty($user)) {
            #Compare credentials for authentication
            if ($this->security->Verify($credential, $user['password'])) {
                #Init new Session
                $session = Session::getInstance();

                #Insert needed parameters for login
                $session->id = $user['id'];

                #Generate fingerprint and update the account last activity
                $this->account->RegenerateFingerprint();
                $this->account->CreateLastActive();

                #Return true
                return true;

            } #Else the user credentials are wrong
            else {
                #Create text for log the error in db
                $error = "Authentication Error [Password]";

                #Log the error in the db
                $this->login_model->insertError($error, $this->request->getIPAddress());

                #Die whit error
                die($error);
            }
        } #Else the user not exist
        else {
            #Create text for log the error in db
            $error = "Username error";

            #Log the error in the db
            $this->login_model->insertError($error, $this->request->getIPAddress());

            #Die whit error
            die($error);
        }
    }
}
