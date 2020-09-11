<?php

use Controllers\CardController;
use Controllers\CharacterController;

$cardController = CardController::getInstance();
$charController= CharacterController::getInstance();

$character= $cardController->getCharacterCardId($_POST['character']);

?>


<div id="card-container">
    <div class="equalizer"></div>
    <div class="internal-container">
        <?php  require(VIEWS.'/Card/Pages/Card-Menu.php'); ?>


        <div class="content-container">
             <?php require(VIEWS.'/Card/Pages/Card-Main.php'); ?>
        </div>

        <input type="hidden" id="PGID" data-character="<?=$character;?>">
    </div>
</div>