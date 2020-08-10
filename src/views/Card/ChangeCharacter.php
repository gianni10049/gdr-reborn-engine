<?php

# Call needed classes
use Controllers\CharacterController;
use Controllers\SessionController;
use Libraries\Security;

# Init needed classes
$sec = Security::getInstance();
$mineChar = SessionController::getInstance()->character;
$characterController = CharacterController::getInstance();

# Get passed characters array
$characters = $_POST['characters'];
?>

<div class="change-character-box">

    <?php


    # Foreach character
    foreach ($characters as $character) {

        # Filter needed vars
        $id = (int)$sec->Filter($character['id'], 'Int');
        $name = $sec->Filter($character['name'], 'String');
        $img = $sec->Filter($character['select_image'], 'Url');
        $favorite = $sec->Filter($character['favorite'], 'Bool');

        # Know if the character is selected
        $selected = ($mineChar == $id);

        # If character exist and owner is the account of the player
        if ($characterController->CharacterExistence($id) && $characterController->CharacterProperty($id)) {
            ?>

            <div class="single-character">

                <!-- NAME -->
                <div class="character-name">
                    <span><?= $name;
                        if ($selected) {
                            echo ' (Selezionato)';
                        } ?></span>
                </div>

                <!-- PREFERRED -->
                <div class="character-favorite">
                    <form method="post" class="character-favorite-form">
                        <input type="hidden" name="character" value="<?= $id; ?>">
                        <button type="submit">
                            <?php if ($favorite) { ?>
                                <i class="fas fa-heart" title="Preferito"></i>
                            <?php } else { ?>
                                <i class="far fa-heart" title="Non Preferito"></i>
                            <?php } ?>
                        </button>
                    </form>
                </div>

                <!-- IMAGE -->
                <div class="character-image">
                    <img src='<?= $img; ?>' alt="Selection image"/>
                </div>

                <!-- STATS -->
                <div class="character-stats">
                    <?php

                    #Foreach character extract stats
                    foreach ($characterController->getCharacterStats($id) as $stat) {

                        #Filter needed values
                        $name = $sec->Filter($stat['name'], 'String');
                        $value = $sec->Filter($stat['value'], 'Int');
                        ?>

                        <div class="single-stat"><?= $name; ?> : <span> <?= $value; ?> </span></div>

                    <?php } ?>
                </div>

                <form method="POST" class="ChangeCharacterForm">
                    <input type="hidden" name="character_id" value="<?= $id; ?>">

                    <?php if (!$selected) { ?>
                        <input type="submit" value="Scegli">
                    <?php } ?>

                </form>
            </div>

            <?php
        }
    }
    ?>

    <div class="character-extra-option">
        <ul>
            <li class="Logout">
                <a href="/LogoutCharacter" title="Esci dal personaggio corrente">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </li>
            <li class="Favorite">
                <a href="/LeaveFavorite" title="Deseleziona preferito">
                    <i class="fas fa-heart-broken"></i>
                </a>
            </li>
        </ul>
    </div>

    <script src="assets/JS/Card/ChangeCharacter.js"></script>

</div>
