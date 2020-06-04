<?php

#Import namespace
use Core\Request,
    Core\Router;

#Import required files
require ('config/required.php');

#Import composer autoloader
require(ROOT.'vendor/autoload.php');

#Import site header
require (ROOT.'public/header/header.php');

#Init Request and Router classes
$request= Request::getInstance();
$router = Router::getInstance($request);

#Routing

#GET
$router->get('/', function($args) {

});

#POST
$router->post('/data', function($args) {

});

#Import site footer
require (ROOT.'public/footer/footer.php');