<div id="UsernameRecovery" class="container_page">

    <form method="POST" id="UsernameRecoveryForm">

        <label for="email">Email</label>
        <input type="text" name="email" id="email">

        <input type="hidden" name="operation" value="UsernameRecovery">
        <input type="submit" value="Richiedi">

    </form>

    <a href="/">Indietro</a>

    <script src="<?= \Libraries\Security::getInstance()->NoChace('/assets/JS/Homepage/UsernameRecovery.js');?>"></script>
</div>