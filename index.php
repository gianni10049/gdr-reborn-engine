<?php

require ('config/required.php');
require(__DIR__.'/vendor/autoload.php');

$router = new Router(new Request());
$db= new DB();

$data= $db->Select('personaggio','nome',"1");

foreach ($data as $row){
    var_dump($row['nome']);
}

$router->get('/', function($args) {

});

$router->get('/data', function($args) {


});

$router->post('/data', function($args) {


});