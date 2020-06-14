<?php

#Import namespace
use Core\Router,
    Libraries\Request,
    Controllers\Template;

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
        echo $tpl->Render('Homepage', []);
    }

});

#Signin view
$router->get('/Signin', function ($args) {

    #Init Template
    $tpl = new Template();

    #Call signin view
    $tpl->Render('Signin',[]);

});


#Signin view
$router->get('/Logout', function ($args) {

    #Init Session class
    $session= \Libraries\Session::getInstance();

    #Destroy session
    echo ($session->destroy()) ? header('Location:/') : 'Errore durante il logout, contattare un\'amministratiore';


});
#POST
/**!
 * ! All post parameter are passed only by forms
 * ! All parameter in $args var, in the callback, are associated like: ['key'=>'val','key'=>'val']
 * !*/


#Login
$router->post('/login', function ($args) {

    #Init Auth class
    $auth = \Controllers\Auth::getInstance();

    #If response is success refresh the page, else echo errors
    echo $auth->ManageError($auth->authenticate($args['username'], $args['pass']));

});

#Signin operation
$router->post('/Signin', function ($args) {

    #Init Signin class
    $signin= \Controllers\Signin::getInstance();

    #Echo response of the sign in operation
    echo $signin->ManageError($signin->AccountRegistration($args));

});

#Import site footer
require(ROOT . 'public/footer/footer.php');