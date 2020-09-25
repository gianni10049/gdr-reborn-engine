<?php

use Libraries\Security;

$sec= Security::getInstance();

$id= $sec->Filter($_POST['character'],'Int');

?>


<div class="card-menu-container">
    <ul class="card-menu">
        <li><a href="/Card-Main?character=<?=$id;?>" class="complete"><span>Scheda</span></a></li>
        <li><a href="/Card-Background?character=<?=$id;?>" class="internal"><span>Background</span></a></li>
        <li><span>Inventario</span></li>
        <li><span>Abilità</span></li>
        <li><span>Modifica</span></li>
    </ul>
</div>