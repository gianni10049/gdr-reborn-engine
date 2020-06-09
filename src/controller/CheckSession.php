<?php


namespace Controllers;

use Core\Config,
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
     * @var Config $config
     */
    private
        $session,
        $request,
        $sec,
        $config;

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
        $this->config = Config::getInstance();
    }

    /**
     * @fn Check
     * @note Check if session is valid
     * @return boolean
     */
    public function Check(): bool
    {

        #Set init value to return
        $return = true;

        #Set array whit controls
        $controls = [
            $this->CheckTimeout(),
            $this->CheckFingerprint()
        ];

        #Foreach control
        foreach ($controls as $control) {

            #If one control is false
            if ($control == false) {

                #Set failed the control
                $return = false;

                #Stop foreach
                break;
            }
        }

        #Return control response
        return $return;
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