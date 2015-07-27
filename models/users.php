<?php

use Phalcon\Mvc\Model;

/**
 * Class for a model of user
 */
class Users extends Model {

    protected $email;
    protected $firstname;
    protected $lastname;
    protected $password;
    protected $isAdmin;

    /**
     * Get the table that corresponds to the model
     * 
     * @return the table that corresponds to the model
     */
    public function getSource() {
        $this->useDynamicUpdate(true);
        return 'user';
    }

    /**
     * Sets the relations between the models
     */
    public function initialize() {
        $this->hasMany("id", "Posts", "user_id", array("alias" => "User"));
        $this->hasMany("id", "Comments", "user_id", array("alias" => "User"));
    }

    public function isExpired() {
        return date("Y-m-d H:i:s", time()) >= $this->expires;
    }

    public function matchingIpAddresses() {
        return trim(Functions::getClientIpAddress()) === trim($this->ip_address);
    }

    public function setEmail($email) {
        if (!Validator::isValidEmail($email)) {
            echo "email";
            Errorhandler::throwRestException();
        }
        $this->email = Filter::filterText($email);
    }

    public function getEmail() {
        return $this->email;
    }

    public function setFirstname($firstname) {
        if (Validator::isEmpty($firstname)) {
            Errorhandler::throwRestException();
        }
        $this->firstname = Filter::filterText($firstname);
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setLastname($lastname) {
        if (Validator::isEmpty($lastname)) {
            Errorhandler::throwRestException();
        }
        $this->lastname = Filter::filterText($lastname);
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setPassword($password) {
        if (!Validator::isValidPassword($password)) {
            Errorhandler::throwRestException();
        }
        $this->password = Filter::filterText($password);
    }

    public function getPassword() {
        return $this->password;
    }

    public function setIsAdmin($isAdmin) {
        if (!Validator::isValidBit($isAdmin)) {
            Errorhandler::throwRestException();
        }
        $this->isAdmin = $isAdmin;
    }

    public function getIsAdmin() {
        if ($this->isAdmin === "1") {
            return true;
        }
        return false;
    }

}
