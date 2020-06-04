<?php

namespace Core;

class Router
{
    /**
     * @var IRequest class
     * @var array Supported methods
     *
     */
    private $request;
    private $supportedHttpMethods;
    public static $_instance;

    /**
     * Self Instance
     * @param Request $request
     * @return Router class
     */
    public static function getInstance($request)
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self($request);
        }
        return self::$_instance;
    }

    /**
     * Router constructor.
     * @param IRequest $request
     */
    public function __construct(IRequest $request)
    {
        $this->supportedHttpMethods = Config::getInstance()->AllowedMethods;
        $this->request = $request;
    }

    /**
     * Magic Method for not allowed methods and request
     * @param $name
     * @param $args
     */
    public function __call($name, $args)
    {

        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;

    }

    /**
     * Exclude extra data from url
     * @param string $route
     * @return string
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');

        if ($result === '') {
            return '/';
        } else {
            $exp = explode('?', $result);
            return $exp[0];
        }
    }

    /**
     * Handler for invalid method of request
     */
    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    /**
     * Handler for not existend method of request
     */
    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    /**
     * Resolve routing of the url and return callback whit parameters
     * @return mixed
     */
    function resolve()
    {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        $formatedRoute = $this->formatRoute($this->request->requestUri);
        $method = $methodDictionary[$formatedRoute];

        if (is_null($method)) {
            $this->defaultRequestHandler();
            die();
        }

        return call_user_func($method, array($this->request->getBody()));
    }

    /**
     * desctruct method
     */
    function __destruct()
    {
        $this->resolve();
    }

}