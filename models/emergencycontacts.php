<?php

use Phalcon\Mvc\Model;

/**
 * Class for a model of emergencycontact
 */
class Emergencycontacts extends Model {

    /**
     * Get the table that corresponds to the model
     * 
     * @return the table that corresponds to the model
     */
    public function getSource() {
        return 'emergencycontact';
    }

    /**
     * Sets the relations between the models
     */
    public function initialize() {
        $this->belongsTo("registration_id", "Registrations", "id", array("alias" => "Contacts"));
    }

}
