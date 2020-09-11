<?php

# Call needed classes
use Controllers\CardController;
use Controllers\CharacterController;
use Libraries\Security;

# Init needed classes
$sec = Security::getInstance();
$characterController = CharacterController::getInstance();
$cardController = CardController::getInstance();

$character = $cardController->getCharacterCardId($_POST['character']);
$data = $characterController->getCharacter($character);

$avatar_image = empty($data['avatar_image']) ? DEFAULT_AVATAR_IMG : $sec->Filter($data['avatar_image'], 'Url');
$name = $sec->Filter($data['name'], 'String');
$surname = $sec->Filter($data['surname'], 'String');
$title = $sec->Filter($data['title'], 'String');

$stats = $characterController->getCharacterStats($character);
?>

<div class="main-card">
    <div class="equalizer"></div>
    <div class="card-pic">
        <img src="<?= $avatar_image; ?>">
    </div>
    <div class="card-info">

        <div class="card-info-internal">
            <div class="card-info-name">
                <?= $name . ' ' . $surname; ?>
            </div>
            <div class="card-info-title">
                <?= $title; ?>
            </div>
            <div class="card-info-data">
                <div class="card-info-data-table">
                    <table>
                        <tbody>
                        <tr>
                            <td class="column100 column1" data-column="column1">Agenzia</td>
                            <td class="column100 column2" data-column="column2">Lone Wolf</td>
                        </tr>
                        <tr>
                            <td class="column100 column1" data-column="column1">Ruolo</td>
                            <td class="column100 column2" data-column="column2">Giustiziere</td>
                        </tr>
                        <tr>
                            <td class="column100 column1" data-column="column1">Residenza</td>
                            <td class="column100 column2" data-column="column2">New York</td>
                        </tr>
                        <tr>
                            <td class="column100 column1" data-column="column1">Nascita</td>
                            <td class="column100 column2" data-column="column2">09/08/1990</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-info-data-table">
                    <table>
                        <tbody>
                        <?php foreach ($stats as $stat) {
                            $stat_name = $sec->Filter($stat['name'], 'String');
                            $stat_value = $sec->Filter($stat['value'], 'Int'); ?>

                            <tr>
                                <td class="column100 column1" data-column="column1"><?= $stat_name; ?></td>
                                <td class="column100 column2" data-column="column2"><?= $stat_value; ?></td>
                            </tr>

                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>