<?php /** @noinspection PhpUndefinedMethodInspection */

require ('config/required.php');
require(ROOT.'vendor/autoload.php');

require (ROOT.'public/header/header.php');

$router = new Router(new Request());

$router->get('/', function($args) {

});

$router->get('/data', function($args) {


});

$router->post('/data', function($args) {


});