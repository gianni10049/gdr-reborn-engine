<?php

namespace Libraries;

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
        $headers;

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

        #Restart whit charset
        $this->headers['content-type'] =  'text/plain;charset=utf-8';

        #Return new email object
        return $this;
    }

    /**
     * @fn SendMail
     * @note Send an email whit the indicated data
     * @param array $to
     * @param string $from
     * @param string $subject
     * @param string $message
     * @param array $headers
     * @param bool $html
     * @return void
     */
    public function SendEmail(array $to, string $from, string $subject, string $message, array $headers = [], bool $html = false)
    {
        $this->setTo($to)
            ->setFrom($from)
            ->setSubject($subject)
            ->setMessage($message,$html)
            ->addGenericHeaders($headers)
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
            $this->headers['From'] = $email;
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
            $this->headers[$header] = $this->security->Filter($value,'String');
        }

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
            mail($email, $this->subject, $this->message, $this->headers);
        }
    }

    /**
     * @fn SendAllAccount
     * @note Send an email to all accounts
     * @return void
     */
    public function SendAll($array)
    {
        #Foreach email
        foreach ($array as $email) {

            #Add the recipient to array
            $this->to[] = $email;
        }
    }
}