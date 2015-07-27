<?php

class Validator {

    /**
     * Can be used to check if a date is valid
     * 
     * @param String  $date  String containing a date
     *
     * @return bool true/false if date is valid
     */
    static public function isValidDate($date) {
        $dt = DateTime::createFromFormat("Y-m-d", $date);
        return $dt !== false && !array_sum($dt->getLastErrors());
    }

    /**
     * Can be used to check if a time is valid
     * 
     * @param String  $time  String containing a time
     *
     * @return bool true/false if time is valid
     */
    static public function isValidTime($time) {
        $dt = DateTime::createFromFormat('H:i', $time);
        $errors = $dt->getLastErrors();
        return $dt !== false && $errors['error_count'] == 0;
    }

    /**
     * Can be used to check if an email address is valid
     * 
     * @param String  $email  An email address
     *
     * @return bool true/false if email address is valid
     */
    static public function isValidEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    /**
     * Can be used to check if a password is valid
     * 
     * @param String  $password  A password
     *
     * @return bool true/false if password is valid
     */
    static public function isValidPassword($password) {
        if (strlen(Filter::filterText($password)) >= 4 && strlen(Filter::filterText($password)) <= 50) {
            return true;
        }
        return false;
    }

    /**
     * Can be used to check if a string is empty
     * 
     * @param String  $string  A string
     *
     * @return bool true/false if string is empty
     */
    static public function isEmpty($string) {
        return empty($string);
    }

    /**
     * Can be used to check if a bit is valid (eg. isFeatured)
     * 
     * @param String  $bit  A string containing a bit
     *
     * @return bool true/false if bit is valid
     */
    static public function isValidBit($bit) {
        if (strlen($bit) === 1 && ($bit === "0" || $bit === "1")) {
            return true;
        }
        return false;
    }

}
