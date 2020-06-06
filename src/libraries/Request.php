<?php

namespace Libraries;

/** 
 *  @class      Request
 *  
 *  Management request
 */
class Request
{
    /**
     * @fn getProtocol
     *  
     * Name and revision of the information protocol via 
     * which the page was requested; e.g. 'HTTP/1.0'; 
     * 
     * @return string
     */
    public function getProtocol(): string
    {
        return strtolower($_SERVER['SERVER_PROTOCOL']);
    }

    /**
     * @fn getIPAddress 
     * 
     * Client IP
     * 
     * @return string
     */
    public function getIPAddress(): string
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        
        else
            $ipaddress = '127.0.0.1';
        
        return $ipaddress;
    }

    /**
     * @fn getUserAgent 
     * 
     * User Agent Client
     * 
     * @return mixed
     */
    public function getUserAgent()
    {
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ' ';
    
        return $agent;
    }

    /**
     * @fn getMethod 
     * 
     * Which request method was used to access the page; e.g. 'GET', 'HEAD', 'POST', 'PUT'.
     * 
     * @return string
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @fn getURI 
     * 
     * The URI which was given in order to access this page; 
     * for instance, '/index.html'.
     * 
     * @return string
     */
    public function getURI(): string
    {
        return strtolower($_SERVER['REQUEST_URI']);
    }

    /**
     * @getHeader
     *
     * Fetches all HTTP headers from the current request.
     * 
     * @param  string $header
     * @return string|null
     */
    public function getHeader(string $header)
    {
        $headers = getallheaders();
            
        if(isset($headers[$header]))
        {
            return $headers[$header];
        }
    }

}