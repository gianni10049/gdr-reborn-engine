<?php

use Controllers\CharacterController;

switch ($_POST['operation']) {

    case 'SetFavorite':

        #Init AccountController class
        $controller = CharacterController::getInstance();

        #Echo response of the favorite set
        echo $controller->SetFavorite($_POST['character']);
        break;

    case 'LeaveFavorite':

        # Init AccountController class
        $controller = CharacterController::getInstance();

        # Echo response of the recovery password operation
        echo $controller->LeaveFavorite();
        break;

    case 'ChangeCharacter':

        #Init AccountController class
        $controller = CharacterController::getInstance();

        #Echo response of the character change
        echo $controller->ChangeCharacter($_POST['character_id']);
        break;

    case 'LogoutCharacter':

        # Init character controller
        $character = CharacterController::getInstance();

        # Logout character
        echo $character->Logout();
        break;
}