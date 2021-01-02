<div id="Homepage" class="container_page">
    <div class="Title"> Reborn V0.1</div>

    <div class="homepage_login_container">

        <form method="POST" id="LoginForm">

            <label for="username">Username</label><br>
            <input type="text" name="username" id="username"><br>


            <label for="password">Password</label><br>
            <input type="password" name="pass" id="password"><br>
            <input type="hidden" name="operation" value="Login">

            <input type="submit" value="Entra">

        </form>

        <div class="homepage_other">

            <div class="other_voice"><a href="/Signin"> Registrati </a></div>
            <div class="other_voice"><a href="/UsernameRecovery"> Recupero Username </a></div>
            <div class="other_voice"><a href="/PasswordRecovery"> Recupero Password </a></div>

        </div>

    </div>

    <script src="<?= \Libraries\Security::getInstance()->NoChace('/assets/JS/Homepage/Homepage.js');?>"></script>

</div>