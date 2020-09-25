<?php

namespace Core;

use Controllers\AccountController;
use Libraries\Request;
use Libraries\Template;
use Models\Config;

/**
 * @class Router
 * @package Core
 * @note Router class for manage routing of the web page
 */
class Router
{
    /**
     * Init vars PRIVATE
     * @var Request class
     * @var array Supported methods
     */
    private
        $supportedHttpMethods;

    /**
     * Init vars PUBLIC
     * @var Request
     * @var AccountController
     */
    public
        $request,
        $account;

    /**
     * Init vars PUBLIC STATIC
     * @var Router $_instance ;
     */
    public static
        $_instance;

    /**
     * @fn getInstance
     * @note Self Instance
     * @param Request $request
     * @return Router class
     */
    public static function getInstance(Request $request): Router
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self($request);
        }
        return self::$_instance;
    }

    /**
     * @return bool
     */
    public function is_ajax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    /**
     * @fn __construct
     * @note Router constructor.
     * @param Request $request
     * @return void
     */
    private function __construct(Request $request)
    {
        $this->supportedHttpMethods = Config::getInstance()->request_allowed_methods;
        $this->account = AccountController::getInstance();
        $this->request = $request;
    }

    public function StartRouting(){

        $method = $this->request->getMethod();
        $uri = $this->request->requestUri;

        # Call function for that method
        $this->{$method}($this->formatRoute($uri), function ($args) {
            $tpl = new Template();
            echo $tpl->Render($args);
        });
    }

    /**
     * @fn __call
     * @note Magic Method for not allowed methods and request
     * @param string $name
     * @param array $args
     * @return void
     */
    public function __call(string $name, array $args)
    {

        #Create an array whit methods
        list($route, $method) = $args;

        #If method not allowed
        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {

            #Get invalid method error and stop script (405)
            $this->invalidMethodHandler();
        }

        #Set method association whit route
        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * @fn formatRoute
     * @note Exclude extra data from url
     * @param string $route
     * @return string
     */
    private function formatRoute(string $route)
    {
        #Trim slash from route
        $result = rtrim($route, '/');

        #If position is root
        if ($result === '') {
            return ($this->account->AccountConnected()) ? '/Lobby' : '/Homepage';
        } #Else not is root
        else {

            #Slice route from passed data
            $exp = explode('?', $result);

            #Return sliced route
            return $exp[0];
        }
    }

    /**
     * @fn invalidMethodHandler
     * @note Handler for invalid method of request
     * @return void
     */
    public function invalidMethodHandler()
    {
        die("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    /**
     * @fn defaultRequestHandler
     * @note Handler for not existent route
     * @return void
     */
    private function defaultRequestHandler()
    {
        die("{$this->request->serverProtocol} 404 Not Found");
    }

    /**
     * @fn resolve
     * @note Resolve routing of the url and return callback whit parameters
     * @return mixed
     */
    function resolve()
    {
        #Call needed vars
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        $formatedRoute = $this->formatRoute($this->request->requestUri);
        $method = $methodDictionary[$formatedRoute];


        #If route not exist in router
        if (is_null($method)) {

            #Get not existent page error (404)
            $this->defaultRequestHandler();
        }

        $body = $this->request->getBody($formatedRoute);

        if($body['Page'] != false){
            #Return callback whit passed args
            return call_user_func($method, $this->request->getBody($formatedRoute));
        }
        else{
            $this->defaultRequestHandler();
        }


    }

    /**
     * @fn addRoutes
     * @note Get all php files whit routes in folder and sub-folders
     * @return void
     */
    public function addRoutes($routes_folder)
    {
        # Start container array for all routes
        $alldirs = [];

        # If folder exist and is correct
        if (is_dir($routes_folder)) {

            # Extract all sub-folders and files in the folder
            $files = glob("{$routes_folder}/*"); //GLOB_MARK adds a slash to directories returned

            # Foreach extracted path
            foreach ($files as $file) {
                # Get dir extension
                $extention = pathinfo($file, PATHINFO_EXTENSION);

                # If is a php file
                if ($extention == 'php') {

                    # Add the path to the general paths array
                    array_push($alldirs, $file);
                } else {

                    # Scan sub-folder and repeat process
                    $this->addRoutes($file);
                }
            }
        }

        # Foreach extracted dir
        foreach ($alldirs as $dir) {

            # Include extracted dir
            require($dir);
        }
    }

    /**
     * @fn __destruct
     * @note Get route and passed data
     * @return void
     */
    function __destruct()
    {
        $this->resolve();
    }


}