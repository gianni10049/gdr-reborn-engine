<?php

namespace Controller;

use Libraries\Security;
use Models\Account;


/**
 * @class Mailer
 * @package Controller
 * @note Class used for send email
 */
class Mailer
{

    /**
     * Init vars PUBLIC STATIC
     * @var Mailer $_instance
     */
    public static
        $_instance;

    /**
     * Init vars PROTECTED
     * @var array $to
     * @var string $subject
     * @var string $message
     * @var array $headers
     * @var string $header
     */
    protected
        $to = [],
        $subject,
        $message,
        $headers = [],
        $header;

    /**
     * Init vars PRIVATE
     * @var Security $security
     */
    private
        $security;

    /**
     * @fn getInstance()
     * @note Self-instance
     * @return Mailer
     */
    public static function getInstance(): Mailer
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
     * @note Mailer constructor.
     * @return void
     */
    public function __construct()
    {
        #Init security class
        $this->security = Security::getInstance();

        #Resets the class properties.
        $this->reset();
    }

    /**
     * @fn reset
     * @note Resets all properties to initial state.
     * @return Mailer
     */
    public function reset(): Mailer
    {
        #Reset all vars to empty
        $this->to = array();
        $this->headers = array();
        $this->subject = null;
        $this->message = null;

        #Return new email object
        return $this;
    }


    public function SendEmail(string $to, string $from, string $subject, string $message, array $headers = [])
    {
        $this->setTo($to)
            ->setFrom($from)
            ->setSubject($subject)
            ->setMessage($message)
            ->addGenericHeaders($headers)
            ->getheadersToSend()
            ->send();
    }

    /**
     * @fn setTo
     * @note Set "to" value of the email
     * @param array $emails (The email address to send to)
     * @return Mailer
     */
    public function setTo(array $emails = []): Mailer
    {
        #Foreach email
        foreach ($emails as $email) {

            #If is a valid email
            if($this->security->EmailControl($email)) {

                #Add recipient
                $this->to[] = $email;
            }
        }

        #Return Mailer class
        return $this;
    }

    /**
     * @fn setFrom
     * @note Set "From" value of the email
     * @param string $email (The email to send as from)
     * @return Mailer
     */
    public function setFrom(string $email): Mailer
    {
        #If Email is correct
        if($this->security->EmailControl($email)) {

            #set From value
            $this->headers[] = $email;
        }

        #Return Mailer class
        return $this;
    }

    /**
     * @fn setSubject
     * @note Set "Subject" value of the email
     * @param string $subject (The email subject)
     * @return Mailer
     */
    public function setSubject(string $subject): Mailer
    {
        #Filter and set subject of the email
        $this->subject = $this->security->Filter($subject, 'String');

        #Return Mailer class
        return $this;
    }

    /**
     * @fn setMessage
     * @note Set message to send in email
     * @param string $message (The message to send)
     * @param bool $html
     * @return Mailer
     */
    public function setMessage(string $message, bool $html = false): Mailer
    {
        #If is html
        if ($html) {

            #Filter in html filter
            $message = $this->security->HtmlFilter($message);
        }

        #Replace wrap in the message
        $this->message = str_replace("\n.", "\n..", $this->security->Filter($message, 'Convert'));

        #Return Mailer class
        return $this;
    }

    /**
     * @fn addGenericHeader
     * @note Add generic headers to the email
     * @param array $headers
     * @return Mailer
     */
    public function addGenericHeaders(array $headers): Mailer
    {

        #Foreach header in array
        foreach ($headers as $header => $value) {

            #Add header to the email
            $this->headers[] = sprintf('%s: %s', $header, $value);
        }

        #Return Mailer class
        return $this;
    }

    /**
     * @fn getheadersToSend
     * @note Return compressed headers array
     * @return Mailer
     */
    public function getheadersToSend(): Mailer
    {
        #Implode header array
        $this->header = implode('"\r\n"', $this->headers);

        #Return Mailer class
        return $this;
    }

    /**
     * @fn send
     * @note send the composed email
     * @return void
     */
    public function send()
    {

        #Foreach recipient
        foreach ($this->to as $email){

            #Send email
            mail($email, $this->subject, $this->message, $this->header);
        }
    }

    /**
     * @fn SendAllAccount
     * @note Send an email to all accounts
     * @return void
     */
    public function SendAllAccount()
    {
        #Start Account instance
        $accounts = Account::getInstance();

        #Get all account emails
        $emails = $accounts->AccountEmails();

        #Foreach email
        foreach ($emails as $email) {

            #Add the recipient to array
            $this->to[] = $email;
        }
    }
}