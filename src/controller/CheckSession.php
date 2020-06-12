<?php


namespace Controllers;

use Models\ConfigModel,
    Libraries\Request,
    Libraries\Security,
    Libraries\Session;

/**
 * @class CheckSession
 * @package Controllers
 * @brief Controller used for control sessions infos
 */
class CheckSession
{

    /**
     * Init Vars PRIVATE
     * @var Session $session
     * @var Request $request
     * @var Security $sec
     * @var ConfigModel $config
     */
    private
        $session,
        $request,
        $sec,
        $config;

    /**
     * Init vars PUBLIC STATIC
     * @var CheckSession $_instance
     */
    public static
        $_instance;

    /**
     * @fn __construct
     * @note CheckSession constructor.
     * @return void
     */
    public function __construct()
    {
        $this->session = Session::getInstance();
        $this->request = Request::getInstance();
        $this->sec = Security::getInstance();
        $this->config = ConfigModel::getInstance();
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @return CheckSession
     */
    public static function getInstance(): CheckSession
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
    public function SessionExist():bool
    {
        #If session exist return true, else false
        return (isset($_SESSION)) ? true : false;
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
    public function CheckTimeout():bool
    {
        #Get timeout of the session
        $timeout = $this->config->session_timeout;

        #Control if session is timed out
        return (time() < ($this->session->last_active + $timeout)) ? true : false;
    }

    /**
     * @fn CheckFingerprint
     * @note Check if session fingerprint is the same than stored
     * @return bool
     */
    public function CheckFingerprint():bool
    {
        #Generate Fingerprint for this session
        $fingerprint = $this->sec->GenerateFingerprint();

        #Control if fingerprint of the session is the same than stored
        return ($this->session->fingerprint == $fingerprint) ? true : false; //ip and ua
    }


}