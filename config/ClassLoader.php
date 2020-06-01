<?php


$controllers = "src/controller";

// Open a directory, and read its contents
if (is_dir($controllers)) {
    if ($dh = opendir($controllers)) {
        while (($file = readdir($dh)) !== false) {
            if (($file != '.') && ($file != '..')) {
                include_once 'src/controller/' . $file;
            }
        }
        closedir($dh);
    }
}

$models = "src/model";

// Open a directory, and read its contents
if (is_dir($models)) {
    if ($dh = opendir($models)) {
        while (($file = readdir($dh)) !== false) {
            if (($file != '.') && ($file != '..')) {
                include_once 'src/model/' . $file;
            }
        }
        closedir($dh);
    }
}


