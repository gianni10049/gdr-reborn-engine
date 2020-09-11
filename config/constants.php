<?php

/* PATHS */
define('ROOT', __DIR__ . '/../');
define('ASSETS', __DIR__ . '/../assets/');
define('SRC', __DIR__ . '/../src/');
define('CONFIG', __DIR__ . '/');
define('MODELS', __DIR__ . '/../src/models/');
define('CONTROLLERS', __DIR__ . '/../src/controller/');
define('LIBRARIES', __DIR__ . '/../src/libraries/');
define('DATABASE', __DIR__ . '/../src/database/');
define('VIEWS', __DIR__ . '/../src/views/');


/* LOGIN RESPONSE */
define('LOGIN_SUCCESS',1);
define('LOGIN_USERNAME_ERROR',2);
define('LOGIN_PASSWORD_ERROR',3);
define('LOGIN_EMPTY_VALUES',4);
define('LOGIN_MAX_ATTEMPTS',5);

/* REGISTARTION RESPONSE */
define('REGISTRATION_SUCCESS', 1);
define('REGISTRATION_USER_ERROR', 2);
define('REGISTRATION_EMAIL_ERROR', 3);
define('REGISTRATION_PASS_ERROR', 4);
define('REGISTRATION_EMPTY_ERROR', 5);

/* PASSWORD UPDATE RESPONSE */
define('PASS_UPDATE_SUCCESS', 1);
define('PASS_UPDATE_MATCH_ERROR', 3);
define('PASS_UPDATE_CONTROL_ERROR', 2);

/* EMAIL UPDATE RESPONSE */
define('EMAIL_UPDATE_SUCCESS', 1);
define('EMAIL_UPDATE_MATCH_ERROR', 2);

/* PASSWORD RECOVERY RESPONSE */
define('PASSWORD_RECOVERY_SUCCESS',1);
define('PASSWORD_RECOVERY_UPDATE_ERROR',2);
define('PASSWORD_RECOVERY_CREATION_ERROR',3);
define('PASSWORD_RECOVERY_CONFIRM_ERROR',4);
define('PASSWORD_RECOVERY_EXISTENCE_ERROR',5);

/* USERNAME RECOVERY RESPONSE */
define('USERNAME_RECOVERY_SUCCESS',1);
define('USERNAME_RECOVERY_EXISTENCE_ERROR',2);
define('USERNAME_RECOVERY_EMAIL_ERROR',3);

/* CARD DEFAULT VALUES */
define('DEFAULT_AVATAR_IMG','/assets/img/Card/default_avatar.jpg');