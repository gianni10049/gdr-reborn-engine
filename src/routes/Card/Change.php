<?php

use Controllers\CharacterController;
use Libraries\Template;
use Core\Router;
use Libraries\Request;

$request = Request::getInstance();
$router = Router::getInstance($request);

#!-- GET

#ChangeCharacter view
$router->get('/ChangeCharacter', function ($args) {

    $char= new CharacterController();
    $charlist= $char->CharactersList();


    #Init Template
    $tpl = new Template();

    #Call signin view
    $tpl->Render('Card/ChangeCharacter', ['characters'=>$charlist]);

});

# Logout Character view
$router->get('/LogoutCharacter',function($args){

    # Init character controller
    $character = CharacterController::getInstance();

    # Logout character
    echo $character->Logout();
});

# Leave favorite character view
$router->get('/LeaveFavorite', function ($args) {

    # Init AccountController class
    $controller = CharacterController::getInstance();

    # Echo response of the recovery password operation
    echo $controller->LeaveFavorite();
});

#!-- POST

# Change character operation
$router->post('/ChangeCharacter', function ($args) {

    #Init AccountController class
    $controller = \Controllers\CharacterController::getInstance();

    #Echo response of the character change
    echo $controller->ChangeCharacter($args['character_id']);

});

# Set favorite character operation
$router->post('/SetFavoriteCharacter', function ($args) {

    #Init AccountController class
    $controller = \Controllers\CharacterController::getInstance();

    #Echo response of the favorite set
    echo $controller->SetFavorite($args['character']);

});

?>