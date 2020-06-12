<?php

/* PATHS */
define('ROOT', __DIR__ . '/../');
define('ASSETS', __DIR__ . '/../assets/');
define('SRC', __DIR__ . '/../src/');
define('CONFIG',__DIR__.'/');
define('MODELS',__DIR__.'/../src/models/');
define('CONTROLLERS',__DIR__.'/../src/controller/');
define('LIBRARIES',__DIR__.'/../src/libraries/');
define('DATABASE',__DIR__.'/../src/database/');
define('VIEWS',__DIR__.'/../src/views/');

/* REGISTARTION RESPONSE */
define('REGISTRATION_PASS_ERROR',1);
define('REGISTRATION_USER_ERROR',2);
define('REGISTRATION_EMAIL_ERROR',3);
define('REGISTRATION_SUCCESS',4);

/* PASSWORD UPDATE RESPONSE */
define('PASS_UPDATE_MATCH_ERROR',1);
define('PASS_UPDATE_CONTROL_ERROR',2);
define('PASS_UPDATE_SUCCESS',3);

/* EMAIL UPDATE RESPONSE */
define('EMAIL_UPDATE_MATCH_ERROR',1);
define('EMAIL_UPDATE_SUCCESS',2);