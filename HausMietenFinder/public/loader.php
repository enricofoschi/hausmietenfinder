<?php

/**
 * Read the configuration
 */
$config = include __DIR__ . "/../app/config/config.php";
/**
 * Read the pwd configuration
 */
$pwd_file = __DIR__ . "/../app/config/main.pwd.php";
if(file_exists($pwd_file)) {
    include $pwd_file;
} else {
    include __DIR__ . "/../app/config/main.pwd.removeme.php";
}

/**
 * Read auto-loader
 */
include __DIR__ . "/../app/config/loader.php";

/**
 * Read services
 */
include __DIR__ . "/../app/config/services.php";
