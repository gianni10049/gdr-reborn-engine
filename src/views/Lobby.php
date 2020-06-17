<?php

$account = \Models\Account::getInstance();

$username = $account->username;

?>

<div id="Lobby">
    Bentornato <?= $username; ?>!<br>
    <a href="/Logout">Esci</a>
</div>

<script src="/assets/JS/Main/Lobby.js"></script>