<?php

namespace Controller;

class Mailer
{

    /**
     * @var array $to
     */
    protected $to = array();

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
    protected $headers = array();

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

    
}