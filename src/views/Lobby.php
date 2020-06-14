<?php

$account= \Models\Account::getInstance();

$username = $account->username;

?>

Bentornato <?=$username;?>!<br>
<a href="/Logout">Esci</a>
