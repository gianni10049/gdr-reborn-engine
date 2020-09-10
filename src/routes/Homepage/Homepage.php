<?php

use Libraries\Template;
use Core\Router;
use Libraries\Request;

$request = Request::getInstance();
$router = Router::getInstance($request);

#!-- GET

#Signin view
$router->get('/Signin', function ($args) {

    #Init Template
    $tpl = new Template();

    #Call signin view
    $tpl->Render('Homepage/Signin', []);

});

#Password Recovery view
$router->get('/PasswordRecovery', function ($args) {

    #Init Template
    $tpl = new Template();

    #Call signin view
    $tpl->Render('Homepage/PasswordRecovery', []);

});

#Username Recovery view
$router->get('/UsernameRecovery', function ($args) {

    #Init Template
    $tpl = new Template();

    #Call signin view
    $tpl->Render('Homepage/UsernameRecovery', []);

});


#!-- POST
#Login operation
$router->post('/login', function ($args) {

    #Init Login class
    $auth = \Controllers\LoginController::getInstance();

    #If response is success refresh the page, else echo errors
    echo $auth->ManageError($auth->authenticate($args['username'], $args['pass']));

});

#Signin operation
$router->post('/Signin', function ($args) {

    #Init Signin class
    $signin = \Controllers\Signin::getInstance();

    #Echo response of the sign in operation
    echo $signin->AccountRegistration($args);

});

#Password Recovery operation
$router->post('/PasswordRecovery', function ($args) {

    #Init AccountController class
    $controller = \Controllers\AccountController::getInstance();

    #Echo response of the recovery password operation
    echo $controller->PasswordRecovery($args['username'], $args['email']);

});

#Username Recovery operation
$router->post('/UsernameRecovery', function ($args) {

    #Init AccountController class
    $controller = \Controllers\AccountController::getInstance();

    #Echo response of the recovery password operation
    echo $controller->UsernameRecovery($args['email']);

});

?>