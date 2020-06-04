<?php

#Base Config and Constants
require(__DIR__ . '/config.php');
require(__DIR__ . '/constants.php');

#Routing
require(CONFIG . 'Routing/IRequest.php');
require(CONFIG . 'Routing/Request.php');
require(CONFIG . 'Routing/Router.php');

#Model, Controller, Views - Loader
require(CONFIG . 'FilesLoader.php');