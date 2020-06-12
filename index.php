<?php

#Import namespace
use Core\Router,
    Libraries\Request;

#Import required files
require('config/required.php');

#Import composer autoloader
require(ROOT . 'vendor/autoload.php');

#Import site header
require(ROOT . 'public/header/header.php');

#Init Request and Router classes
$request = Request::getInstance();
$router = Router::getInstance($request);

#Routing

#GET
/**!
 * ! All get parameter need to be passed by syntax : "/data?key=val&key=val&key=val";
 * ! Root syntax for passing parameter is: "/?key=val&key=val&key=val";
 * ! All parameter in $args var, in the callback, are associated like: ['key'=>'val','key'=>'val']
 * !*/

$router->get('/', function ($args) {
});

#POST
/**!
 * ! All post parameter are passed only by forms
 * ! All parameter in $args var, in the callback, are associated like: ['key'=>'val','key'=>'val']
 * !*/

$router->post('/data', function ($args) {

});

#Import site footer
require(ROOT . 'public/footer/footer.php');