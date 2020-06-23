<div class="change-character-box">
    <?php

    # Init needed classes
    $sec = \Libraries\Security::getInstance();
    $mineChar = \Controllers\SessionController::getInstance()->character;

    # Get passed characters array
    $characters = $_POST['characters'];

    # Foreach character
    foreach ($characters as $character) {

        # Filter needed vars
        $id = $sec->Filter($character['id'], 'Int');
        $name = $sec->Filter($character['name'], 'String');
        $img = $sec->Filter($character['select_image'], 'Url');

        # Know if the character is selected
        $selected = ($mineChar === $id);

        ?>

        <div class="single-character">
            <form method="POST" class="ChangeCharacterForm">

                <div class="character-name">
                    <span><?= $name;
                        if ($selected) {
                            echo ' (Selezionato)';
                        } ?></span>
                </div>
                <div class="character-image">
                    <img src='<?= $img; ?>' alt="Selection image"/>
                </div>
                <div class="character-stats">
                    <div class="single-stat">Forza : <span>10</span></div>
                    <div class="single-stat">Destrezza : <span>5</span></div>
                    <div class="single-stat">Intelletto : <span>3</span></div>
                    <div class="single-stat">Carisma : <span>3</span></div>
                </div>

                <input type="hidden" name="character_id" value="<?= $id; ?>">

                <?php if (!$selected) { ?>
                    <input type="submit" value="Scegli">
                <?php } ?>

            </form>
        </div>

    <?php } ?>

    <div class="character-logout">
        <a href="/LogoutCharacter" title="Esci dal personaggio corrente">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>

    <script src="assets/JS/Card/ChangeCharacter.js"></script>

</div>
