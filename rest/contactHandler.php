<?php

require APP_UTILITIES . "/response.php";

//Autoload RestFunctions object
$restFunctions = new RestFunctions();

//Get contact info
$app->get('/api/contact/info', function() use ($app, $restFunctions) {
    $phql = "SELECT id, phoneNr, email, streetName, place, postal, fax, website  FROM InformationJoetz LIMIT 1";
    HttpResponse::showJsonReponse($restFunctions->getListOfObjects($app, $phql)); 
});
