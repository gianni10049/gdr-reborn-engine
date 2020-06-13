<?php

namespace Controllers;

use Libraries\Security,
    Models\Account;

class Signin
{
    /**
     * Init vars PRIVATE
     * @var Security $security
     * @var Account $account
     * @var CheckAccount $checkaccount
     */
    private
        $security,
        $account,
        $checkaccount;

    /**
     * @fn __constructor
     * @note Init needed classes
     * @return void
     */
    private function __construct()
    {
        #Call instances of the needed classes
        $this->security = Security::getInstance();
        $this->checkaccount = CheckAccount::getInstance();
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
        #Validate post input
        $post = $this->security->Filter($post, 'Post');

        #Filter passed data
        $user = $this->security->Filter($post['username'], 'Convert');
        $email = $this->security->Filter($post['Email'], 'Email');
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
}