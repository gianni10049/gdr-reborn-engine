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
$env = \Libraries\Enviroment::getInstance();

#If not is ajax
if (!$router->is_ajax()) {

    #Import site header
    require(ROOT . 'public/header/header.php');
}

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

#Include other routes
$router->addRoutes($env->ROUTES_FOLDER);

/*********************************
 **** LOGOUT
 **********************************/
#Logout view
$router->get('/Logout', function ($args) {

    #Init needed classes
    $session = \Controllers\SessionController::getInstance();
    $tpl = new Template();

    #Call signin view
    $tpl->Render('Logout', ['response'=>$session->destroy()]);
});

#Import site footer
if (!$router->is_ajax()) {
    require(ROOT . 'public/footer/footer.php');
}