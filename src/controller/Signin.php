<?php

namespace Controllers;

use Libraries\Security;
use Models\Account;

/**
 * @class Sigin
 * @note Controller for register accounts and characters
 * @package Controllers
 */
class Signin
{
    /**
     * Init vars PRIVATE
     * @var Security $security
     * @var Account $account
     * @var AccountController $checkaccount
     */
    private
        $security,
        $account,
        $checkaccount;

    /**
     * Init vars PUBLIC STATIC
     * @var Signin $_instance
     */
    public static
        $_instance;

    /**
     * @fn getInstance()
     * @note Self-instance
     * @return Signin
     */
    public static function getInstance(): Signin
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
     * @fn __constructor
     * @note Init needed classes
     * @return void
     */
    private function __construct()
    {
        #Call instances of the needed classes
        $this->security = Security::getInstance();
        $this->checkaccount = AccountController::getInstance();
        $this->account = Account::getInstance();
    }


    /**
     * @fn AccountRegistration
     * @note Sign in new accounts
     * @param array $post (Input)
     * @return int
     */
    public function AccountRegistration(array $post): int
    {

        #Filter passed data
        $user = $this->security->Filter($post['username'], 'Convert');
        $email = $this->security->Filter($post['email'], 'Email');
        $pass = $this->security->Filter($post['password'], 'Convert');
        $passVerification = $this->security->Filter($post['password_verification'], 'Convert');

        #If password is correct
        if ( $this->security->PasswordMatch($pass, $passVerification) && $this->security->PasswordControl($pass) ) {

            #If username non exist
            if ($this->account->UsernameExist($user)) {

                #If email not exist
                if ($this->account->EmailExist($email) && $this->security->EmailControl($email)) {

                    #Insert new account
                    $this->account->NewAccount($user, $email, $pass);

                    #Return success response
                    return REGISTRATION_SUCCESS;

                } #Else email exist
                else {

                    #Return email error
                    return REGISTRATION_EMAIL_ERROR;
                }

            } #Else username exist
            else {

                #Return username error
                return REGISTRATION_USER_ERROR;
            }

        } #Else password is not correct
        else {

            #Return password error
            return REGISTRATION_PASS_ERROR;
        }
    }


    /**
     * @fn ManageError
     * @note Manage response of Signin
     * @param $response
     * @return string
     */
    public function ManageError($response)
    {
        #Init html var
        $html = '';

        switch ((int)$response) {

            #Case success
            case (int)REGISTRATION_SUCCESS:
                $html .= 'Registrazione avvenuta con successo!';
                $html .= '<br> <a href="/">Torna alla home</a>';
                break;

            #Case username error
            case (int)REGISTRATION_USER_ERROR:
                $html .= 'Username già utilizzato o non valido.';
                $html .= '<br> <a onclick="history.go(-1)">Riprova</a>';
                break;

            #Case password error
            case (int)REGISTRATION_PASS_ERROR:
                $html .= 'Password non valida. Deve contenere un carattere maiuscolo, uno minuscolo, una lettera, un carattere speciale ed essere compresa tra un minimo di 5 ed un massimo di 16 caratteri';
                $html .= '<br> <a onclick="history.go(-1)">Riprova</a>';
                break;

            #Case email error
            case (int)REGISTRATION_EMAIL_ERROR:
                $html .= 'Email già utilizzata o non valida.';
                $html .= '<br> <a onclick="history.go(-1)">Riprova</a>';
                break;

            #Default case
            default:
                $html .= 'Errore sconosciuto, contattare lo staff via email!';
                $html .= '<br> <a href="/">Torna alla home</a>';
                break;
        }

        #Return composed email
        return $html;
    }

}