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

#POST
/**!
 * ! All post parameter are passed only by forms
 * ! All parameter in $args var, in the callback, are associated like: ['key'=>'val','key'=>'val']
 * !*/


#Login
$router->post('/login', function ($args) {

    #Init Auth class
    $auth = \Controllers\Auth::getInstance();

    #Get Auth response
    $Response= $auth->authenticate($args['username'], $args['pass']);

    #If response is success refresh the page, else manage errors
    ($Response === LOGIN_SUCCESS) ? header(' Location: /;') : $auth->ManageError($Response);

});

#Import site footer
require(ROOT . 'public/footer/footer.php');