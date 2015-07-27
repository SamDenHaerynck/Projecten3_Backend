<?php

/**
 * Application and database settings
 */
return new \Phalcon\Config(array(
    'database' => array(
        'adapter' => 'Mysql',
        'host' => 'eu-cdbr-azure-west-b.cloudapp.net',
        'username' => 'b0aea4ba814764',
        'password' => 'f7a0db3c',
        'dbname' => 'projecten3groep6',
        'charset' => 'utf8',
        "options" => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        )
    ),
    'application' => array(
        'modelsDir' => APP_PATH . '/models/',
        'viewsDir' => APP_PATH . '/views/',
        'baseUri' => '/test/',
    )
        ));
