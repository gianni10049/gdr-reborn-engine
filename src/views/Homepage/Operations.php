<?php

use Controllers\AccountController;
use Controllers\LoginController;
use Controllers\Signin;

switch ($_POST['operation']) {

    case 'UsernameRecovery':
        #Init AccountController class
        $controller = AccountController::getInstance();

        #Echo response of the recovery password operation
        echo $controller->UsernameRecovery($_POST['email']);
        break;

    case 'PasswordRecovery':
        #Init AccountController class
        $controller = AccountController::getInstance();

        #Echo response of the recovery password operation
        echo $controller->PasswordRecovery($_POST['username'], $_POST['email']);
        break;

    case 'Signin':
        #Init Signin class
        $signin = Signin::getInstance();

        #Echo response of the sign in operation
        echo $signin->AccountRegistration($_POST);
        break;

    case 'Login':
        #Init Login class
        $auth = LoginController::getInstance();

        #If response is success refresh the page, else echo errors
        echo $auth->ManageError($auth->authenticate($_POST['username'], $_POST['pass']));
        break;


}