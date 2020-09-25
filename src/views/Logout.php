<?php

use Controllers\SessionController;

#Init needed classes
$session = SessionController::getInstance();

$response = $session->destroy();

if ($response) { ?>
    <div id="Logout">
        Torna presto!<br>
        <a href="/">Torna alla home</a>
    </div>
<?php } ?>

<script src="/assets/JS/Homepage/Logout.js"></script>