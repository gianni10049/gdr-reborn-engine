<?php

# Call needed classes
use Controllers\CardController;
use Controllers\CharacterController;
use Libraries\Security;

# Init needed classes
$sec = Security::getInstance();
$characterController = CharacterController::getInstance();
$cardController = CardController::getInstance();

# Get and control passed character id
$character = $cardController->getCharacterCardId($_GET['character']);

# Get character data
$data = $characterController->getCharacter($character);
?>

<div class="background-card">
    <?php
    if ($characterController->CharacterExistence($character)) {
        echo $sec->HtmlFilter($data['background']);
    } else { ?>
        <p class="card-error"> Personaggio non esistente.</p>
    <?php } ?>
</div>
