<?php

namespace Core;

include_once 'IRequest.php';

class Request implements IRequest
{

    /**
     * @var Request Self-Instance
     * @var string $_SERVER ['REQUEST_METHOD']
     * @var string $_SERVER ['REQUEST_URI']
     * @var string $_SERVER ['SERVER_PROTOCOL']
     */
    public static $_instance;
    public $requestMethod;
    public $requestUri;
    public $serverProtocol;

    /**
     * Request constructor.
     * Auto generate array whit server  info
     */
    function __construct()
    {
        $this->bootstrapSelf();
    }

    /**
     * Self instance
     * @return Request Self-Instance
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Auto generate server infos
     */
    private function bootstrapSelf()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    /**
     * Convert server info to CamelCase format
     * @param string $string
     * @return array
     */
    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

    /**
     * Take parameters passed by get request
     * @return array
     */
    private function getArgs()
    {
        $url = $_SERVER['REQUEST_URI'];
        $args = [];

        //Split data from base url
        $data = explode('?', $url);

        //If parameters exist
        if (!empty($data[1])) {

            //Split single parameters
            $dataArgs = explode('&', $data[1]);

            //Foreach parameter
            foreach ($dataArgs as $arg) {

                //Explode value
                $vals = explode('=', $arg);

                //Get key and val
                $key = $vals[0];
                $val = $vals[1];

                //Create array voice
                $args[$key] = $val;

            }

            //Return array with parameters
            return $args;
        } else {
            return [];
        }
    }

    /**
     * Take parameters passed by post request
     * @return array
     */
    private function postArgs()
    {
        $args = array();
        foreach ($_POST as $key => $value) {
            $args[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $args;
    }

    /**
     * Fetch types of request method for get right parameters
     * @return array
     */
    public function getBody()
    {
        if ($this->requestMethod === "GET") {
            return $this->getArgs();
        } else if ($this->requestMethod == "POST") {
            return $this->postArgs();
        } else {
            die('Method not allowed.');
        }
    }
}