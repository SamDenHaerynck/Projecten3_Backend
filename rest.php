<?php

// Creates the autoloader
$loader = new \Phalcon\Loader();

$loader->registerClasses(
        array(
            "RestFunctions" => "restFunctions.php",
        )
);

// register autoloader
$loader->register();

//Lazy load rest handler files 

/* @var $_SERVER type */
$request_uri = htmlspecialchars(str_replace("%2F","/",$_SERVER['REQUEST_URI']));

$request = explode("/", $request_uri);
$api_index = array_search("api", $request, true);

if ($api_index !== FALSE && $api_index !== count($request) - 1) {
    try {
        $handlerName = $request[++$api_index];
        $file = APP_PATH . '\rest\\' . $handlerName . 'Handler.php';

        if (!file_exists($file)) {
            throw new Exception;
        }
        include $file;
    } catch (Exception $ex) {
        $app->response->setStatusCode(404, "Not Found test")->sendHeaders();
        echo $app['view']->render('404');
        die();
    }
}

