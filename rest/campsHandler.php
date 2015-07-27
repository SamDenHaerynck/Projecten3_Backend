<?php

require APP_UTILITIES . "/response.php";

//Autoload RestFunctions object
$restFunctions = new RestFunctions();

//Get all camps for camp overview
$app->get('/api/camps', function() use ($app, $restFunctions) {

    $phql = "SELECT c.id, title, city, period, substring(promotext, 1,50) as promotext, location, isFeatured, min(minAge) minimumAge, max(maxAge) maximumAge
        FROM Camps c LEFT JOIN Pictures p ON c.id = p.camp_id 
        LEFT JOIN Groups g ON c.id = g.camp_id
        GROUP BY c.id 
        ORDER BY title";
    HttpResponse::showJsonReponse($restFunctions->getListOfObjects($app, $phql));
});

//Get all camps with all fields
$app->get('/api/camps/all', function() use ($app, $restFunctions) {

    $phql = "SELECT c.id, c.period, c.title, c.city, c.place, c.transport, c.maxParticipants, c.price, "
            . "c.starPrice1, c.starPrice2, c.extraInfo, c.isDeductible, c.promotext, c.isFeatured, p.location, COUNT(*) registrations,  min(minAge) minimumAge, max(maxAge) maximumAge "
            . "FROM Camps c LEFT JOIN Registrations r on c.id = r.camp_id "
            . "LEFT JOIN Pictures p ON c.id = p.camp_id "
            . "LEFT JOIN Groups g ON c.id = g.camp_id "
            . "GROUP BY c.id";
    HttpResponse::showJsonReponse($restFunctions->getListOfObjects($app, $phql));
});

//Get camp by id
$app->get('/api/camps/{id:[0-9]+}', function($id) use ($app, $restFunctions) {

    $phql = "SELECT c.id, c.period, c.title, c.city, c.place, c.transport, c.maxParticipants, c.price, "
            . "c.starPrice1, c.starPrice2, c.extraInfo, c.isDeductible, c.promotext, COUNT(r.id) registrations "
            . "FROM Camps c LEFT JOIN Registrations r on c.id = r.camp_id WHERE c.id = :id:";
    HttpResponse::showJsonReponse($restFunctions->getSingleObject($app, $phql, $id));
});

//Get included items from camp
$app->get('/api/camps/{id:[0-9]+}/extra', function($id) use ($app, $restFunctions) {

    $phql1 = "SELECT id, itemText FROM Includeditems WHERE camp_id = $id";
    $phql2 = "SELECT id, phoneNr, email FROM InformationJoetz";
    $phql3 = "SELECT minAge, maxAge FROM Groups WHERE camp_id = $id";
    $phql4 = "SELECT startDate, endDate FROM Timespans WHERE camp_id = $id";
    
    $data = array(
        'includeditems' => $restFunctions->getListOfObjects($app, $phql1),
        'informationjoetz' => $restFunctions->getListOfObjects($app, $phql2),
        'ages' => $restFunctions->getListOfObjects($app, $phql3),
        'timespans' => $restFunctions->getListOfObjects($app, $phql4)
    );
    
    HttpResponse::showJsonReponse($data);
});

//Get camp pictures by camp id
$app->get('/api/camps/{id:[0-9]+}/images', function($id) use ($app, $restFunctions) {
    $phql = "SELECT id, location FROM Pictures WHERE camp_id = $id";
    HttpResponse::showJsonReponse($restFunctions->getListOfObjects($app, $phql));
});

//URI to submit camp registration
$app->post('/api/camps/{id:[0-9]+}/signup', function($id) use ($app) {

    //Validate  + catch errors
    $jsonData = json_decode($app->request->getRawBody(), true);

    $registration = new Registrations();
    $address = new Addresses();

    $registration->memberNumber1 = $jsonData['memberNumber1'];
    $registration->memberNumber2 = $jsonData['memberNumber2'];
    $registration->extraInfo = $jsonData['extraInfo'];
    $registration->camp_id = $id;

    $address->place = $jsonData['place'];
    $address->postalNr = $jsonData['postal'];
    $address->street = $jsonData['street'];
    $address->streetNr = $jsonData['nr'];
    $address->bus = $jsonData['bus'];

    $registration->address = $address;

    $registration->save();

    foreach ($jsonData['emergencyContacts'] as $contact) {
        $emergencyContact = new Emergencycontacts();

        $emergencyContact->firstName = $contact['fName'];
        $emergencyContact->lastName = $contact['lName'];
        $emergencyContact->phoneNr = $contact['phone'];

        $emergencyContact->registration_id = $registration->id;
        $emergencyContact->save();
    }

    foreach ($jsonData['parents'] as $p) {
        $parent = new Parents();

        $parent->firstName = $p['fName'];
        $parent->lastName = $p['lName'];
        $parent->phoneNr = $p['phone'];
        $parent->nationalNumber = $p['natNr'];
        $parent->email = $p['mail'];

        $address = new Addresses();
        $address->place = $p['place'];
        $address->postalNr = $p['postal'];
        $address->street = $p['street'];
        $address->streetNr = $p['nr'];
        $address->bus = $p['bus'];

        $address->save();

        $parent->address = $address;

        $parent->registration_id = $registration->id;
        $parent->save();
    }

    foreach ($jsonData['participants'] as $p) {
        $participant = new Participants();

        $participant->firstName = $p['fName'];
        $participant->lastName = $p['lName'];
        $participant->dateOfBirth = date("Y-m-d H:i:s", strtotime($p['birthdate']));
        $participant->nationalNumber = $p['natNr'];

        $participant->registration_id = $registration->id;
        $participant->save();
    }
});

//URI to send 200 OK to angular
$app->options('/api/camps/{id:[0-9]+}/signup', function() use ($app, $restFunctions) {
    HttpResponse::showOk();
});
