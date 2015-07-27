<?php

use Phalcon\Mvc\Model;

/**
 * Class for a model of registration
 */
class Registrations extends Model {

    /**
     * Get the table that corresponds to the model
     * 
     * @return the table that corresponds to the model
     */
    public function getSource() {
        return 'registration';
    }

    /**
     * Sets the relations between the models
     */
    public function initialize() {
        $this->belongsTo("address_id", "Addresses", "id", array("alias" => "Address"));
        $this->hasMany("id", "Emergencycontacts", "registration_id", array("alias" => "Contacts"));
        $this->hasMany("id", "Parents", "registration_id", array("alias" => "Parents"));
        $this->hasMany("id", "Parents", "registration_id", array("alias" => "Participants"));
    }

}
