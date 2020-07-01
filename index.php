<?php

#Import namespace
use Libraries\Template;
use Core\Router;
use Libraries\Request;

#Import required files
require('config/required.php');

#Import composer autoloader
require(ROOT . 'vendor/autoload.php');


#Init Request and Router classes
$request = Request::getInstance();
$router = Router::getInstance($request);

#If not is ajax
if (!$router->is_ajax()) {

    #Import site header
    require(ROOT . 'public/header/header.php');
}

#Routing

/**! GET
 * ! All get parameter need to be passed by syntax : "/data?key=val&key=val&key=val";
 * ! Root syntax for passing parameter is: "/?key=val&key=val&key=val";
 * ! All parameter in $args var, in the callback, are associated like: ['key'=>'val','key'=>'val']
 * !*/

#Root switch
$router->get('/', function ($args) {

    #Init Account and Template class
    $account = \Controllers\AccountController::getInstance();
    $tpl = new Template();

    #If connected
    if ($account->AccountConnected()) {

        #Render the Lobby
        echo $tpl->Render('Lobby', $args);
    } #Else is not connected
    else {

        #Render the homepage
        echo $tpl->Render('Homepage/Homepage', []);
    }

});

#Signin view
$router->get('/Signin', function ($args) {

    #Init Template
    $tpl = new Template();

    #Call signin view
    $tpl->Render('Homepage/Signin', []);

});

#Password Recovery
$router->get('/PasswordRecovery', function ($args) {

    #Init Template
    $tpl = new Template();

    #Call signin view
    $tpl->Render('Homepage/PasswordRecovery', []);

});

#Username Recovery
$router->get('/UsernameRecovery', function ($args) {

    #Init Template
    $tpl = new Template();

    #Call signin view
    $tpl->Render('Homepage/UsernameRecovery', []);

});

#ChangeCharacter view
$router->get('/ChangeCharacter', function ($args) {

    $char= new \Controllers\CharacterController();
    $charlist= $char->CharactersList();


    #Init Template
    $tpl = new Template();

    #Call signin view
    $tpl->Render('Card/ChangeCharacter', ['characters'=>$charlist]);

});

# Logout Character
$router->get('/LogoutCharacter',function($args){

    # Init character controller
    $character = \Controllers\CharacterController::getInstance();

    # Logout character
    echo $character->Logout();
});

#Signin view
$router->get('/Logout', function ($args) {

    #Init needed classes
    $session = \Controllers\SessionController::getInstance();
    $tpl = new Template();

    #Call signin view
    $tpl->Render('Logout', ['response'=>$session->destroy()]);
});

/**! POST
 * ! All post parameter are passed only by forms
 * ! All parameter in $args var, in the callback, are associated like: ['key'=>'val','key'=>'val']
 * !*/


#Login
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

# Change character
$router->post('/ChangeCharacter', function ($args) {

    #Init AccountController class
    $controller = \Controllers\CharacterController::getInstance();

    #Echo response of the recovery password operation
    echo $controller->ChangeCharacter($args['character_id']);

});

#Import site footer
if (!$router->is_ajax()) {
    require(ROOT . 'public/footer/footer.php');
}