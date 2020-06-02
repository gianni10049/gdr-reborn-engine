<?php

require ('header_css.php');
require ('header_js.php');

#Set utf-8 the php default language
mb_internal_encoding('UTF-8');

#Set utf-8 the browser default language
mb_http_output('UTF-8');

#Set utf-8 the html default language
header('Content-Type:text/html; charset=UTF-8');
