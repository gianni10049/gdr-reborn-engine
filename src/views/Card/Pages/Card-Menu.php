<?php

use Libraries\Security;
use Libraries\Template;

$sec = Security::getInstance();
$tpl = new Template();

$id = $sec->Filter($_POST['character'], 'Int');


?>


<div class="card-menu-container">
    <ul class="card-menu">
        <?php
        # Foreach menu voice
        foreach ($tpl->MenuList('card-menu') as $menu) {

            # Filter data and print results
            $text_menu = $sec->Filter($menu['text'], 'String');
            $link_menu = $sec->Filter($menu['link'], 'String');
            $container = $tpl->GetContainerForLinks($sec->Filter($menu['link_container'], 'String'));
            ?>

            <li class="list-group-item">
                <a href="<?= $link_menu; ?>?character=<?= $id; ?>" class="<?= $container; ?>">
                    <span><?= $text_menu; ?></span>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>