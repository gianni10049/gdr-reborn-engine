<?php

#Start Instance of Config
use Database\DB;
use Libraries\Enviroment;
use Libraries\Security;
use Libraries\Session;
use Models\Account;
use Libraries\Preprocessor;

#Init instance of Enviroment
$env = Enviroment::getInstance();

#Init instance of DB
$db = DB::getInstance($env->DB_NAME);
$db->Connect();

#Init instance of Security
$sec = Security::getInstance();

#Init instance of Session
$session = Session::getInstance();

#Init instance of Account
$account = Account::getInstance();

# Init preprocessor
$preprocessor = Preprocessor::getInstance();

# Get css link for compiled files
$css = $preprocessor->Compile();

#Import sub-header files
require('header_css.php');
require('header_js.php');

#Set utf-8 the php default language
mb_internal_encoding('UTF-8');

#Set utf-8 the browser default language
mb_http_output('UTF-8');

#Set utf-8 the html default language
header('Content-Type:text/html; charset=UTF-8');

?>

<title> Reborn v0.1</title>
