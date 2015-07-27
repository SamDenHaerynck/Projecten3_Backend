<?php

require APP_AUTH . "/authenticate.php";
require APP_UTILITIES . "/validator.php";
require APP_UTILITIES . "/errorhandler.php";
require APP_UTILITIES . "/filters.php";
require APP_UTILITIES . "/parser.php";
require APP_UTILITIES . "/functions.php";
require APP_UTILITIES . "/response.php";

//Autoload RestFunctions object
$restFunctions = new RestFunctions();

$app->before(function() use ($app) {
    Auth::isAuthorized($app, true);
});

//URI to display camps for an admin
$app->get('/api/admin/camps', function() use ($app, $restFunctions) {
    $phql = "SELECT c.id, title, period, city, place, COUNT(r.id) registrations, maxParticipants, price, isFeatured "
            . "FROM Camps c "
            . "LEFT JOIN Registrations r on c.id = r.camp_id "
            . "GROUP BY c.id";
    HttpResponse::showJsonReponse($restFunctions->getListOfObjects($app, $phql));
});

//URI to display a camp for an admin
$app->get('/api/admin/camps/{id:[0-9]+}', function() use ($app, $restFunctions) {
    $phql = "SELECT c.id, c.period, c.title, c.city, c.place, c.transport, c.maxParticipants, c.price, "
            . "c.starPrice1, c.starPrice2, c.extraInfo, c.isDeductible, c.promotext, c.isFeatured, p.location, COUNT(*) registrations,  min(minAge) minimumAge, max(maxAge) maximumAge "
            . "FROM Camps c LEFT JOIN Registrations r on c.id = r.camp_id "
            . "LEFT JOIN Pictures p ON c.id = p.camp_id "
            . "LEFT JOIN Groups g ON c.id = g.camp_id "
            . "GROUP BY c.id";
    HttpResponse::showJsonReponse($restFunctions->getListOfObjects($app, $phql));
});

//URI to update a camp
$app->put('/api/admin/camps/{id:[0-9]+}', function($id) use ($app) {
    try {
        $jsonData = Functions::getJsonData($app);

        if (!Validator::isValidBit($jsonData['isFeatured'])) {
            throw new Exception();
        }

        //Find Camp by id
        $camp = Camps::findFirst("id = $id");
        $camp->isFeatured = $jsonData['isFeatured'];
        $camp->save();
        HttpResponse::showOk();
    } catch (Exception $ex) {
        Errorhandler::throwRestException();
    }
});

//URI to display users for an admin
$app->get('/api/admin/users', function() use ($app, $restFunctions) {
    $phql = "SELECT id, firstname, lastname, '' as password, email, ip_address, isAdmin "
            . "FROM Users";
    HttpResponse::showJsonReponse($restFunctions->getListOfObjects($app, $phql));
});

//URI to update a user
$app->put('/api/admin/users/{id:[0-9]+}', function($id) use ($app) {
    $jsonData = Functions::getJsonData($app);

    $user = Users::findFirst("id = $id");
    $user->setEmail($jsonData['email']);
    $user->setFirstname($jsonData['firstname']);
    $user->setLastname($jsonData['lastname']);

    if ($jsonData['password'] !== '') {
        $user->setPassword($jsonData['password']);
    }
    $user->setIsAdmin($jsonData['isAdmin']);
    $user->save();
    HttpResponse::showOk();
});

//URI to create a new user
$app->post('/api/admin/users/new', function() use ($app) {

    $jsonData = Functions::getJsonData($app);

    $user = new Users();

    $user->setEmail($jsonData['email']);
    $user->setFirstname($jsonData['firstname']);
    $user->setLastname($jsonData['lastname']);
    $user->setPassword($jsonData['password']);
    $user->setIsAdmin($jsonData['isAdmin']);
    $user->save();
    HttpResponse::showOk();
});

//URI to delete a user
$app->delete('/api/admin/users/{id:[0-9]+}', function($id) use ($app) {
    try {

        $phql = "DELETE FROM Users WHERE id = :id:";
        
        //Execute above query
        $status = $app->modelsManager->executeQuery($phql, array(
            'id' => $id
        ));

        //If delete fails throw exception
        if ($status->success() === false) {
            throw new Exception();
        }
    } catch (Exception $ex) {
        Errorhandler::throwRestException();
    }
});
