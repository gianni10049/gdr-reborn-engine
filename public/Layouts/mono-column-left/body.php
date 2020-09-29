<?php

# Call needed libraries
use Libraries\Security;
use Libraries\Template;

# Start needed classes
$tpl = new Template();
$sec = Security::getInstance();

?>


<div id="mono-column-left" class="container_page">
    <ul class="first-menu">

        <?php

        # Foreach menu voice
        foreach ($tpl->MenuList('left') as $menu) {

        # Filter data and print results
        $id_menu = $sec->Filter($menu['id'], 'Int');
        $text_menu = $sec->Filter($menu['text'], 'String');
        $icon_menu = $sec->Filter($menu['icon'], 'String');
        $clickable_menu = $sec->Filter($menu['clickable'], 'Bool');
        $link_menu = $sec->Filter($menu['link'], 'String');
        $class_menu = $tpl->GetContainerForLinks($sec->Filter($menu['link_container'], 'String'));
        ?>


        <li class="list-group-item">
            <?php

            # If menu is clickable print a tag
            if ($clickable_menu){ ?>
            <a href="<?= $link_menu; ?>" class="<?= $class_menu; ?>">
                <?php } ?>
                <div class="MenuVoice">
                    <div class="MenuImg">
                        <i class="<?= $icon_menu; ?>"></i>
                    </div>
                    <div class="MenuVoiceText"><?= $text_menu; ?></div>
                </div>
                <?php if ($clickable_menu){ ?>
            </a>
        <?php } ?>

            <div class="submenu svg-submenu">

                <?php

                # Foreach submenu voice
                foreach ($tpl->SubMenuList($id_menu) as $submenu) {

                # Filter data and print results
                $id_submenu = $sec->Filter($submenu['id'], 'Int');
                $text_submenu = $sec->Filter($submenu['text'], 'String');
                $icon_submenu = $sec->Filter($submenu['icon'], 'String');
                $clickable_submenu = $sec->Filter($submenu['clickable'], 'Bool');
                $link_submenu = $sec->Filter($submenu['link'], 'String');
                $class_submenu = $tpl->GetContainerForLinks($sec->Filter($submenu['link_container'], 'String'));

                ?>
                <div class="list-group-item">
                    <?php if ($clickable_submenu){ ?>
                    <a href="<?= $link_submenu; ?>" class="<?= $class_submenu; ?>">
                        <?php } ?>
                        <div class="SubmenuItem">
                            <div class="MenuImg">
                                <i class="<?= $icon_submenu; ?>"></i>
                            </div>
                            <div class="SubmenuText">
                                <?= $text_submenu; ?>
                            </div>
                        </div>

                        <?php if ($clickable_submenu){ ?>
                    </a>
                    <?php } ?>
                </div>
                <?php
                } ?>
            </div>
        </li>
        <?php } ?>

    </ul>


    <div class="central-box">

        <div class="content-box">

        </div>

    </div>
</div>


<script src="/public/Layouts/mono-column-left/body.js"></script>