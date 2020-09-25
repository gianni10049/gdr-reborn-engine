<?php

namespace Controllers;

use Libraries\Mailer;
use Libraries\Security;
use Models\Account;
use Models\Config;

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
     * @var Mailer $mailer
     * @var Config $config
     */
    private
        $security,
        $account,
        $checkaccount,
        $mailer,
        $config;

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
        $this->mailer = Mailer::getInstance();
        $this->config = Config::getInstance();
    }


    /**
     * @fn AccountRegistration
     * @note Sign in new accounts
     * @param array $post (Input)
     * @return string
     */
    public function AccountRegistration(array $post):string
    {

        #Filter passed data
        $user = $this->security->Filter($post['username'], 'Convert');
        $email = $this->security->Filter($post['email'], 'String');
        $pass = $this->security->Filter($post['password'], 'Convert');
        $passVerification = $this->security->Filter($post['password_verification'], 'Convert');

        # If all value are not empty
        if(!empty($user) && !empty($email) && !empty($pass) && !empty($passVerification)) {

            #If password is correct
            if ($this->security->PasswordMatch($pass, $passVerification) && $this->security->PasswordControl($pass)) {

                #If username non exist
                if (!$this->account->UsernameExist($user)) {

                    #If email not exist
                    if (!$this->account->EmailExist($email) && $this->security->EmailControl($email)) {

                        #Insert new account
                        $this->account->NewAccount($user, $email, $pass);

                        $subject = 'Iscrizione avvenuta con successo!';
                        $text = "
                        La tua iscrizione è avvenuta con successo!
                         
                        Il tuo nickname é: {$user}
                        La tua password è: {$pass}
                      
                        Buon gioco! ";


                        $this->mailer->SendEmail([$email], $this->config->domain_email, $subject, $text, [], true);

                        # Set success response
                        $response = REGISTRATION_SUCCESS;

                    } #Else email exist
                    else {

                        #Set email error
                        $response = REGISTRATION_EMAIL_ERROR;
                    }

                } #Else username exist
                else {

                    #Set username error
                    $response = REGISTRATION_USER_ERROR;
                }

            } #Else password is not correct
            else {

                #Set password error
                $response = REGISTRATION_PASS_ERROR;
            }

        } # Else one of the value is empty
        else{

            $response = REGISTRATION_EMPTY_ERROR;
        }

        return $this->ManageError($response);

    }


    /**
     * @fn ManageError
     * @note Manage response of Signin
     * @param $response
     * @return string
     */
    public function ManageError($response)
    {
        switch ($this->security->Filter($response,'Int')) {

            #Case success
            case (int)REGISTRATION_SUCCESS:
                $text = 'Registrazione avvenuta con successo!';
                $type = 'success';
                break;

            #Case username error
            case (int)REGISTRATION_USER_ERROR:
                $text = 'Username già utilizzato o non valido.';
                $type = 'warning';
                break;

            #Case password error
            case (int)REGISTRATION_PASS_ERROR:
                $text = 'Password non valida. Deve contenere un carattere maiuscolo, uno minuscolo, una lettera, un carattere speciale ed essere compresa tra un minimo di 5 ed un massimo di 16 caratteri';
                $type = 'info';
                break;

            #Case email error
            case (int)REGISTRATION_EMAIL_ERROR:
                $text = 'Email già utilizzata o non valida.';
                $type = 'error';
                break;

            case (int)REGISTRATION_EMPTY_ERROR:
                $text = 'Uno o più campi vuoti.';
                $type = 'info';
                break;

            #Default case
            default:
                $text = 'Errore sconosciuto, contattare lo staff via email!';
                $type = 'error';
                break;
        }

        $json = ['type'=>$type,'text'=>$text];

        #Return composed email
        return json_encode($json);
    }

}