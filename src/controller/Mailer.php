<?php

namespace Controller;

use Libraries\Security,
    Models\Account;


/**
 * Class Mailer
 * 
 * usage:
 * 
 * $mail = Mailer::getInstance();
 * 
 * $mail->setTo('mail@mail.com')
 *      ->setFrom('webmaster@mail.com)
 *      ->setSubject('Info')
 *      ->setMessage('Hello')
 *      ->send();
 */
class Mailer
{

    /**
     * @var array $to
     */
    protected $to = [];

    /**
     * @var string $subject
     */
    protected $subject;

    /**
     * @var string $message
     */
    protected $message;

    /**
     * @var array $headers
     */
    protected $headers = [];

    /**
     * @var Security $security
     */
    private $security;

    /**
     * Named constructor.
     *
     * @return static
     */
    public static function getInstance()
    {
        return new Mailer();
    }

    /**
     * __construct
     *
     * Resets the class properties.
     */
    public function __construct()
    {
        $this->security = Security::getInstance();
        
        $this->reset();
    }

    /**
     * reset
     *
     * Resets all properties to initial state.
     *
     * @return self
     */
    public function reset()
    {
        $this->to = array();
        $this->headers = array();
        $this->subject = null;
        $this->message = null;

        return $this;
    }

    /**
     * setTo
     *
     * @param string $email The email address to send to.
     *
     * @return self
     */
    public function setTo(string $email = null)
    {
        $this->to[] = ($this->security->setEmail($email) == true) ? $email : false;
        
        return $this;
    }

    /**
     * setFrom
     *
     * @param string $email The email to send as from.
     *
     * @return self
     */
    public function setFrom(string $email)
    {
        $address = ($this->security->setEmail($email) == true) ? $email : false;
        
        $this->headers[] = 'From: ' . $address;

        return $this;
    }

    /**
     * setSubject
     *
     * @param string $subject The email subject
     *
     * @return self
     */
    public function setSubject(string $subject)
    {
        $this->subject = $this->security->Filter($subject);
        
        return $this;
    }

    /**
     * setMessage
     * 
     *
     * @param string $message The message to send.
     *
     * @return self
     */
    public function setMessage(string $message, $html = false)
    {
        if(!$html)
        {
            #vd. https://www.php.net/manual/en/function.mail.php
            $this->message = str_replace("\n.", "\n..", $this->security->Filter($message));
        
            return $this;
        }
        
        #html
        $this->message = $this->security->HtmlFilter($message);
        #vd. https://www.php.net/manual/en/function.mail.php
        $this->message = str_replace("\n.", "\n..", $this->message);

        return $this;
    }

    /**
     * addGenericHeader
     *
     * @param string $header The generic header to add.
     * @param mixed  $value  The value of the header.
     * 
     * Ex.: $this->addGenericHeader(
     *      'Content-Type', 'text/html; charset="utf-8"'
     *  );
     *
     * @return self
     */
    public function addGenericHeader(string $header, string $value)
    {
        $this->headers[] = sprintf('%s: %s', $header, $value);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getheadersToSend()
    {
        $this->header = implode('"\r\n"', $this->headers);

        return $this;
    }

    /**
     * send
     */
    public function send()
    {
        for($i = 0; $i < count($this->to); $i++)
        {
            mail($this->to[$i], $this->subject, $this->message, $this->header);
        }
    }

    /**
     * sendAll
     *
     * @return void
     */
    public function sendAll()
    {
        $accounts = Account::getInstance();

        $emails = $accounts->getAllEmails();

        foreach($emails as $email)
        {
            $this->to[] = $email;
        }
    }
}