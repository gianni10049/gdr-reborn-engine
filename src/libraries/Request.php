<?php

namespace Libraries;

use Database\DB;

/**
 * @class Request
 * @package Libraries
 * @note Manage http requests
 */
class Request
{

    /**
     * Init vars PUBLIC STATIC
     * @var Request $_instance
     */
    public static
        $_instance;

    /**
     * Init vars PUBLIC
     * @var string $requestMethod
     * @var string $serverProtocol
     * @var string $requestUri
     */
    public
        $requestMethod,
        $serverProtocol,
        $requestUri;

    /**
     * Init vars PRIVATE
     * @var Security $sec
     * @var DB $db
     */
    private
        $sec,
        $db;

    /**
     * @fn __construct
     * @note Request constructor.
     * @return void
     */
    private function __construct()
    {
        #Init needed classes
        $this->sec = Security::getInstance();
        $this->db = DB::getInstance();

        #Fetch server info
        $this->bootstrapSelf();
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Request
     */
    public static function getInstance(): Request
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
     * @fn getProtocol
     * @note Get page protocol
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->sec->Filter(strtolower($this->serverProtocol), 'String');
    }

    /**
     * @fn getIPAddress
     * @note Get user public ip address
     * @return string
     */
    public function getIPAddress(): string
    {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = '127.0.0.1';
        }

        return $this->sec->Filter($ipaddress, 'String');
    }

    /**
     * @fn getUserAgent
     * @note Get User Agent Client
     * @return string|void
     */
    public function getUserAgent(): string
    {
        return isset($this->httpUserAgent) ? $this->sec->Filter($this->httpUserAgent, 'String') : '';
    }

    /**
     * @fn getMethod
     * @note Get request Method type
     * @return string
     */
    public function getMethod(): string
    {
        return $this->sec->Filter(strtolower($this->requestMethod), 'String');
    }

    /**
     * @fn getURI
     * @note Get server uri
     * @return string
     */
    public function getURI(): string
    {
        return $this->sec->Filter(strtolower($this->requestUri), 'String');
    }

    /**
     * @fn getHeader
     * @note Fetch all HTTP headers from the current request.
     * @param string $header
     * @return mixed
     */
    public function getHeader(string $header): string
    {
        $headers = getallheaders();

        return isset($headers[$header]) ? $this->sec->Filter($headers[$header]) : false;
    }

    /**
     * @fn getLang
     * @note Get http accepted languages
     * @return string
     */
    public function getLang(): string
    {
        #Return filtered HTTP_ACCEPT_LANGUAGE
        return $this->sec->Filter(strtolower($this->httpAcceptLanguage), 'String');
    }

    /**
     * @fn bootstrapSelf
     * @note Auto generate server infos
     * @return void
     */
    private function bootstrapSelf()
    {
        $sec = $this->sec;

        #Foreach server info
        foreach ($_SERVER as $key => $value) {
            #Create a param
            $this->{$this->toCamelCase($key)} = $sec->Filter($value, 'String');
        }
    }

    /**
     * Convert server info to CamelCase format
     * @param string $string
     * @return string
     */
    private function toCamelCase(string $string): string
    {
        #Lower the string
        $result = strtolower($string);

        #Regex the request for create array of key/values
        preg_match_all('/_[a-z]/', $result, $matches);

        #Foreach couple
        foreach ($matches[0] as $match) {
            #Convert server_protocol to serverProtocol
            $c = str_replace('_', '', strtoupper($match));

            #Set value in the array
            $result = str_replace($match, $c, $result);
        }

        #Return array of converted server infos
        return $result;
    }

    /**
     * @fn getArgs
     * @note Take parameters passed by get request
     * @return array
     */
    private function getArgs(): array
    {

        $url = $this->getURI();
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
        }

        # Return values
        return $args;
    }

    /**
     * @fn postArgs
     * @note Take parameters passed by post request
     * @return array
     */
    private function postArgs(): array
    {
        #Init void array
        $args = array();

        #Foreach parameters
        foreach ($_POST as $key => $value) {
            $args[$key] = $this->sec->Filter($value, 'Convert');
        }


        #Return parameters array
        return $args;
    }

    /**
     * @fn getPage
     * @note Get page whit that alias
     * @param $url string
     * @return int|mixed|string
     */
    public function getPage(string $url): string
    {
        # Filter passed url
        $url = $this->sec->Filter($url, 'String');

        # Get file for that alias
        $data = $this->db->Select('file', 'routes', "alias='{$url}' AND active='1' LIMIT 1")->Fetch();

        # Filter path of the alias
        $path = $this->sec->Filter($data['file'], 'String');

        # Return result
        return $path;
    }

    /**
     * @fn getBody
     * @note Fetch types of request method for get right parameters
     * @param $route string
     * @return array
     */
    public function getBody(string $route): array
    {

        # Get arguments or abort
        if ($this->requestMethod === "GET") {
            $args = $this->getArgs();
        } else if ($this->requestMethod == "POST") {
            $args = $this->postArgs();
        } else {
            die('Method not allowed.');
        }

        # Get page name
        $args['Page'] = $this->getPage($route);

        # Return arguments and page name
        return $args;


    }
}