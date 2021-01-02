<?php

# Call needed classes
use Controllers\CardController;
use Controllers\CharacterController;
use Libraries\Security;

# Init needed classes
$sec = Security::getInstance();
$characterController = CharacterController::getInstance();
$cardController = CardController::getInstance();

# Get and control passed data
$character = $cardController->getCharacterCardId($_POST['character']);
$part = $sec->Filter($_POST['part'], 'Int');

?>


<div class="part-data-card">


    <?php
    if ($characterController->CharacterExistence($character)) {

        $damages = $cardController->PartDamagesList($character, $part);

        foreach ($damages as $damage) {
            $id = $sec->Filter($damage['id'], 'Int');
            $name = $sec->Filter($damage['name'], 'String');
            $description = $sec->Filter($damage['description'], 'Convert');
            $value = $sec->Filter($damage['damage'], 'Int');
            $end_date = $sec->Filter($damage['ending'],'String');
            $end = $sec->LocalTime($end_date, 'd/m');

            ?>

            <div class="single-damage">
                <div class="single-damage-title">
                    <?=$name;?>
                </div>
                <div class="single-damage-body">
                    <div class="single-damage-body-value">
                        Danni : <?=$value;?>
                    </div>
                    <div class="single-damage-body-ending">
                        Fino il : <?=$end;?>
                    </div>
                    <div class="single-damage-body-description">
                        <?=$description;?>
                    </div>
                </div>
            </div>


        <?php }
    } else { ?>
        <p class="card-error"> Personaggio non esistente.</p>
    <?php } ?>

    <script src="<?=$sec->NoChace('/assets/JS/Card/Card-Part-Data.js');?>"></script>

</div>
