<?php

use Libraries\Enviroment;

$env= Enviroment::getInstance();
$layout= $env->LAYOUT_NAME;

?>

<div id="Lobby">
    <?php include (ROOT."/public/Layouts/{$layout}/body.php");?>
</div>

<script src="/assets/JS/Main/Lobby.js"></script>