<?php

class Router
{
    private $request;
    private $supportedHttpMethods = array(
        "GET",
        "POST"
    );
    private $args;

    public function __construct(IRequest $request)
    {
        $this->request = $request;
    }

    public function __call($name, $args)
    {

        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;

    }

    private function formatRoute($route)
    {
        $result = rtrim($route, '/');

        if ($result === '') {
            return '/';
        }
        else{
            $exp= explode('?',$result);

            return $exp[0];
        }
    }

    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    function resolve()
    {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        $formatedRoute = $this->formatRoute($this->request->requestUri);
        $method = $methodDictionary[$formatedRoute];

        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }

        echo call_user_func($method, array($this->request->getBody()));
    }

    function __destruct()
    {
        $this->resolve();
    }

}