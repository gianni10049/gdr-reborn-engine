<div id="Signin" class="container_page">

    <form method="POST" id="SigninForm">

        <label for="username">Username</label>
        <input type="text" name="username" id="username">

        <label for="email">Email</label>
        <input type="text" name="email" id="email">

        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <label for="password_verification">Conferma Password</label>
        <input type="password" name="password_verification" id="password_verification">

        <input type="hidden" name="operation" value="Signin">
        <input type="submit" value="Registrati">
    </form>

    <a href="/">Indietro</a>

    <script src="<?= \Libraries\Security::getInstance()->NoChace('/assets/JS/Homepage/Signin.js');?>"></script>

</div>