<?php

use Phalcon\Mvc\Micro;

error_reporting(E_ALL);

define('APP_PATH', realpath('..'));
define('APP_UTILITIES', realpath('../library/utilities/'));
define('APP_AUTH', realpath('../library/auth/'));
define('APP_EXT_LIBRARIES', realpath('../vendor/'));

try {

    //Read the configuration
    $config = include __DIR__ . "/../config/config.php";

    //Include Services
    include APP_PATH . '/config/services.php';

    //Include Autoloader
    include APP_PATH . '/config/loader.php';

    //Start application
    $app = new Micro($di);

    //Incude Application
    include APP_PATH . '/app.php';
    
    //Include REST operation handler
    include APP_PATH . '/rest.php';
    
    //Handle requests
    $app->handle();
} catch (\Exception $e) {
    echo $e->getMessage();
}
