<?php


use Core\Router;
use Libraries\Request;
use Libraries\Template;

$request = Request::getInstance();
$router = Router::getInstance($request);


#!-- GET

#ChangeCharacter view
$router->get('/Card-Main', function ($args) {

    #Init Template
    $tpl = new Template();

    #Call card-container view
    $tpl->Render('Card/Card-Container',['character'=>$args['character_id']]);
});
