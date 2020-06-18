<?php

#Base Config and Constants
require(__DIR__ . '/constants.php');

#Model, Controller, Views - Loader
require(MODELS . 'Wrapper.php');
require(LIBRARIES . 'FilesLoader.php');

#Preprocessor
require_once ('vendor/css-crush/css-crush/CssCrush.php');