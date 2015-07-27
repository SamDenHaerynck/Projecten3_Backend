<?php

require APP_UTILITIES . "/functions.php";
require APP_AUTH . "/authenticate.php";
require APP_UTILITIES . "/validator.php";
require APP_UTILITIES . "/filters.php";
require APP_UTILITIES . "/email.php";
require APP_UTILITIES . "/response.php";
require APP_UTILITIES . "/errorhandler.php";


//Check if authenticated with Auth::isAuthorized($app);

//URI to login
$app->post('/api/auth/login', function() use ($app) {

    try {
        //Comment this to disable login via HTTP (Local WAMP server)
        if (!Functions::isSecure()) {
            throw new Exception();
        }

        $jsonData = Functions::getJsonData($app);

        if (!filter_var($jsonData['email'], FILTER_VALIDATE_EMAIL) || empty($jsonData['password'])) {
            throw new Exception();
        }

        $user = Users::findFirst("email = '" . $jsonData['email'] . "'");

        if (is_object($user) && $user->getPassword() === $jsonData['password']) {

            //generate token            
            if (!$user->matchingIpAddresses() || $user->isExpired() || empty($user->token)) {
                $user->ip_address = Functions::getClientIpAddress();

                $user->expires = date("Y-m-d H:i:s", time() + 3600);

                $data = $user->expires . $user->id . $user->getEmail() . $user->getIsAdmin();
                $privatekey = "3s1ef3esf3q3f1sf55yjuk";
                $user->token = hash_hmac('sha256', $data, $privatekey);

                $user->save();
            }

            $app->response->setStatusCode(200, "OK");
            $app->response->setJsonContent(array('token' => $user->token, 'expires' => $user->expires, 'email' => $user->getEmail(), 'isAdmin' => $user->getIsAdmin()));
            $app->response->send();
        } else {
            throw new Exception();
        }
    } catch (Exception $ex) {
        $app->response->setStatusCode(401, "Unauthorized");
        $app->response->setContent("Access denied 4");
        $app->response->send();
        die();
    }
});

//URI to send 200 OK to angular
$app->options('/api/auth/login', function() use ($app) {
    HttpResponse::showOk();
});

//URI to logout
$app->post('/api/auth/logout', function() use ($app) {
    $user = Auth::getUser($app);

    //Destroy token and expires of user
    unset($user->token);
    unset($user->expires);
    $user->save();

    $app->response->setStatusCode(200, "OK");
    $app->response->send();
});

//URI to reset forgotten password
$app->post('/api/auth/forgot', function() use ($app) {
    try {
        $jsonData = json_decode($app->request->getRawBody(), true);

        if (!Validator::isValidEmail($jsonData['email'])) {
            throw new Exception();
        }

        $user = Users::findFirst("email = '" . $jsonData['email'] . "'");

        if (!is_object($user)) {
            throw new Exception();
        }

        $user->setPassword(Auth::generateNewPassword());
        $user->save();
        Email::sendPasswordResetMail($user->getEmail(), $user->getPassword());
        HttpResponse::showOk();
    } catch (Exception $ex) {
        Errorhandler::throwRestException();
    }
});

