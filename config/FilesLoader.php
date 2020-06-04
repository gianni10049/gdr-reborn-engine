<?php

# ARRAY OF ROOTS WHERE LOAD FILES
$dirs = [
    "src/controller",
    "src/model"
];

#Foreach Root
foreach ($dirs as $dir) {
    #If Exist Root
    if (is_dir($dir)) {
        #Open Dir
        if ($dh = opendir($dir)) {
            #Foreach file
            while (($file = readdir($dh)) !== false) {
                if (($file != '.') && ($file != '..')) {
                    #Include the file
                    include_once $dir . '/' . $file;
                }
            }
            #Close dir
            closedir($dh);
        }
    }
}