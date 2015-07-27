<?php

class Auth {

    /**
     * Returns a user if a valid access token is provided by
     * X-XSRF-TOKEN in HTTP-headers
     *
     * @param Micro  $app  The The Micro application
     *
     * @return User  The user associated with the access token
     */
    static public function getUser($app) {
        try {
            //Get X-XSRF-TOKEN header
            $token = $app->request->getHeader("HTTP_X_XSRF_TOKEN");

            //Find user by access token
            $user = Users::findFirst("token = '" . $token . "'");

            if (!is_object($user)) {
                throw new Exception();
            }

            return $user;
        } catch (Exception $ex) {
            $app->response->setStatusCode(401, "Unauthorized");
            $app->response->setContent("Access denied");
            $app->response->send();
            die();
        }
    }

    /**
     * Can be used to check if user has the right permissions to perform an action
     *
     * @param Micro  $app  The The Micro application
     * @param bool  $admin  True/false if user needs admin permissions to perform an action
     *
     * @return bool  true/false if user is allowed to perform an action
     */
    static public function isAuthorized($app, $admin) {
        try {
            //Get user from DB
            $user = Auth::getUser($app);

            //Check if user is admin
            if ($admin && !$user->getIsAdmin()) {
                echo "Is not admin";
                throw new Exception();
            }

            //Check if login session is expired or if ip adresses dont match
            if (!is_object($user) || $user->isExpired() || !$user->matchingIpAddresses()) {
                echo is_object($user) ? "Ob: true" : "Ob: false";
                echo $user->isExpired() ? "Ex: true" : "Ex: false";
                echo $user->matchingIpAddresses() ? "IP: true" : "IP: false";
                throw new Exception();
            }

            return true;
        } catch (Exception $ex) {
            $app->response->setStatusCode(401, "Unauthorized");
            $app->response->setContent("Access denied");
            $app->response->send();
            die();
        }
    }

    /**
     * Generates a new random password that is 8 characters long
     *
     * @return String  $pass  the randomly generated password
     */
    static public function generateNewPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array();

        //Generate random password
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    }

}
