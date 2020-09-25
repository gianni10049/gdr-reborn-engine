<?php

#Import namespace
use Core\Router;
use Libraries\Request;
use Libraries\Template;

#Import required files
require('config/required.php');

#Import composer autoloader
require(ROOT . 'vendor/autoload.php');

#Init Request and Router classes
$request = Request::getInstance();
$router = Router::getInstance($request);
$env = \Libraries\Enviroment::getInstance();

# Get method
$method = $request->getMethod();

#If not is ajax
if (!$router->is_ajax()) {
    #Import site header
    require(ROOT . 'public/header/header.php');
}

$router->StartRouting();

#Import site footer
if (!$router->is_ajax()) {
    require(ROOT . 'public/footer/footer.php');
}