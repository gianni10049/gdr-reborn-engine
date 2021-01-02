<?php

use Controllers\CardController;
use Controllers\CharacterController;
use Libraries\Security;

$cardController = CardController::getInstance();
$charController= CharacterController::getInstance();
$sec = Security::getInstance();

$character= $cardController->getCharacterCardId($_GET['character']);


?>


<div id="card-container">
    <div class="equalizer"></div>
    <div class="internal-container">
        <?php  require(VIEWS.'/Card/Pages/Card-Menu.php'); ?>

        <div class="content-container">
             <?php require(VIEWS.'/Card/Pages/Card-Main.php'); ?>
        </div>
    </div>

    <script src="<?=$sec->NoChace('/assets/JS/Card/Card-Container.js');?>"></script>
</div>