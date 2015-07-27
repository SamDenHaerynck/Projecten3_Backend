<?php

class Functions {

    /**
     * Returns client IP address
     * 
     * @return String  $the_ip  the client ip
     */
    static public function getClientIpAddress() {
        //Get the headers if we can or else use the SERVER global
        if (function_exists('apache_request_headers')) {

            $headers = apache_request_headers();
        } else {

            $headers = $_SERVER;
        }

        //Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {

            $the_ip = $headers['X-Forwarded-For'];
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {

            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {

            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }

        return $the_ip;
    }

    /**
     * Can be used to check if connection is HTTPS
     * 
     * @return bool  true/false if currect connection is running over HTTPS or not
     */
    static public function isSecure() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    }

    /**
     * Can be used to get an array of the json data that was send to the server
     *
     * @param Micro  $app  The The Micro application
     * 
     * @return String  $jsonData  Array containing all json data
     */
    static public function getJsonData($app) {
        $jsonData = json_decode($app->request->getRawBody(), true);

        if (is_array($jsonData)) {
            array_walk_recursive($jsonData, function (&$val) {
                $val = Filter::filterText($val);
            });
        }

        return $jsonData;
    }

    /**
     * Can be used to get the current date and time
     * 
     * @return Date  Current date and time
     */
    static public function getCurrentDateTime() {
        return date("Y-m-d H:i:s", time());
    }

}
