<?php

namespace Controllers;

use Libraries\Security;
use Libraries\Request;
use Libraries\Session;
use Models\Login;

class Auth
{

    private $security;
    private $request;
    private $login_model;

    public function __construct()
    {
        $this->security = new Security;
        $this->request = new Request;
        $this->login_model = new Login;
    }

    /**
     * authenticate
     * 
     * @param  string|null $username
     * @param  string|null $credential
     * @return bool
     */
    public function authenticate(string $username = null, string $credential = null): bool
    {

        if((!isset($username)) || (!isset($credential)))
        {
            return false;
        }

        if($this->login_model->countAttempts($this->request->getIpAddress()) > 10)
        {
            return false;
            exit();
        }

        $username = $this->security->Filter($username);

        if(trim($username) == null) return false;

        //recupera, eventualmente, i dati dal db
        $user = $this->model->readByName($username);

        if(!empty($user))
        {
            if($this->security->Verify($credential, $user['password']))
            {
                //CREA LA SESSIONE
                $session = new Session;
                //inserire altre sessioni utili
                //come i permessi
                $session->id = $user['id'];
                $session->username = $user['username'];
                //per la sicurezza
                $session->fingerprint = hash_hmac('sha256', $this->request->getUserAgent(), hash('sha256', $this->request->getIPAddress(), true));
                $session->last_active = time();

                return true;

            }
            else
            {   //scrive l'errore in db
                $error = "Password Error";

                $this->login_model->insertError($error, $this->request->getIPAddress());

                return false;
            }
        }
        else
        {   //scrive l'errore in db
            $error = "Username error";

            $this->login_model->insertError($error, $this->request->getIPAddress());

            return false;

        }
    }
}
