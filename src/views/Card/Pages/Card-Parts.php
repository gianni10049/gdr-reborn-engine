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

?>

<div class="parts-card" data-character="<?= $character; ?>">

    <?php
    if ($characterController->CharacterExistence($character)) { ?>

        <div class="single-part single-part-header">
            <div class="single-part-internal">
                Parte
            </div>
            <div class="single-part-internal">
                Punti Vita
            </div>
            <div class="single-part-internal">
                Stato
            </div>
        </div>

        <?php

        $parts = $characterController->getParts();

        foreach ($parts as $part) {
            $id = $sec->Filter($part['id'], 'Int');
            $name = $sec->Filter($part['name'], 'String');
            $description = $sec->Filter($part['description'], 'String');
            $lifepoint = $cardController->CharacterPartLifepoint($character, $part['id']);
            $remained= $sec->Filter($lifepoint['Remained'],'Int');
            $total = $sec->Filter($lifepoint['Total'],'Int');
            $status_data = $cardController->PartStatus($remained);
            $status = $sec->Filter($status_data['Status'],'String');
            $status_descr = $sec->Filter($status_data['Description'],'String');


            ?>

            <div class="single-part" data-part="<?= $id; ?>">
                <div class="single-part-internal single-part-internal-name" title="<?= $description; ?>">
                    <span class="part-name"> <?= $name; ?></span>
                </div>
                <div class="single-part-internal">
                    <?= $remained; ?>/<?=$total;?>
                </div>
                <div class="single-part-internal" title="<?=$status_descr;?>">
                    <?= $status; ?>
                </div>
            </div>
            <?php
        }

    } else { ?>
        <p class="card-error"> Personaggio non esistente.</p>
    <?php } ?>

    <script src="<?= $sec->NoChace('/assets/JS/Card/Card-Parts.js'); ?>"></script>
</div>
