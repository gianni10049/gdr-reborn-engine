<?php


namespace Controllers;

use Libraries\Request,
    Libraries\Security,
    Libraries\Session,
    Models\Config;
use Models\Account;

/**
 * @class SessionController
 * @package Controllers
 * @brief Controller used for control sessions infos
 */
class SessionController
{

    /**
     * Init Vars PRIVATE
     * @var Session $session
     * @var Request $request
     * @var Security $sec
     * @var Config $config
     */
    private
        $session,
        $request,
        $sec,
        $config;

    /**
     * Init vars PUBLIC STATIC
     * @var SessionController $_instance
     */
    public static
        $_instance;

    /**
     * @fn __construct
     * @note SessionController constructor.
     * @return void
     */
    public function __construct()
    {
        $this->session = Session::getInstance();
        $this->request = Request::getInstance();
        $this->sec = Security::getInstance();
        $this->config = Config::getInstance();
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @return SessionController
     */
    public static function getInstance(): SessionController
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
     * @fn SessionExist
     * @note Control if exist session
     * @return bool
     */
    public function SessionExist(): bool
    {
        #If session exist return true, else false
        return ( isset($_SESSION['id']) );
    }

    /**
     * @fn Check
     * @note Check if session is valid
     * @return boolean
     */
    public function Check(): bool
    {
        #Set array whit controls
        $controls = [
            $this->CheckTimeout(),
            $this->CheckFingerprint()
        ];

        #Foreach control
        foreach ($controls as $control) {

            #If one control is false
            if ($control == false) {

                #Failed the control
                return false;
            }
        }

        #Return true response if not failed
        return true;
    }

    /**
     * @fn CheckSessionTimeout
     * @note Check if session are timed out
     * @return bool
     */
    public function CheckTimeout(): bool
    {

        #Get Account instance
        $account = Account::getInstance();

        #Get timeout of the session
        $timeout = $this->config->session_timeout;

        #Control if session is timed out
        return ( time() < ($account->last_active + $timeout) );
    }

    /**
     * @fn CheckFingerprint
     * @note Check if session fingerprint is the same than stored
     * @return bool
     */
    public function CheckFingerprint(): bool
    {

        #Get Account instance
        $account = Account::getInstance();

        #Generate Fingerprint for this session
        $fingerprint = $this->sec->GenerateFingerprint();

        #Control if fingerprint are the same, for the same type
        $contr=  (($account->fingerprint_ip == $fingerprint['ip']) || ($account->fingerprint_lang == $fingerprint['lang']));

        #Control if fingerprint of the session is the same than stored
        return ($contr) ? true : false;
    }

    /**
     * @fn __get
     * @note Get magic method
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        #Filter entered value
        $name= $this->sec->Filter($name,'String');

        #If session param exist
        if($this->session->$name)
        {
            #Return session param
            return $this->session->$name;
        } #Else not exist
        else{
            #Return false
            return false;
        }
    }

    /**
     * @fn __set
     * @note Set magic method
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, $value)
    {
        #Filter entered value
        $name= $this->sec->Filter($name,'String');

        #Set session value
        $this->session->$name = $value;
    }


    /**
     * @fn destroy
     * @note Destroy method for delete session
     * @return bool
     */
    public function destroy():bool
    {
        #Destroy session
        session_destroy();

        #If is correctly destroyed return true, else return false
        return ( session_status() === PHP_SESSION_NONE );
    }
}