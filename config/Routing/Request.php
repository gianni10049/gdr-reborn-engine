<?php

include_once 'IRequest.php';

class Request implements IRequest
{
    function __construct()
    {
        $this->bootstrapSelf();
    }

    private function bootstrapSelf()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

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
        }
    }

    public function getBody()
    {
        if ($this->requestMethod === "GET") {
            $args = $this->getArgs();
            return $args;
        }


        if ($this->requestMethod == "POST") {

            $args = array();
            foreach ($_POST as $key => $value) {
                $args[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $args;
        }
    }
}