<?php

use Phalcon\Mvc\Model;

/**
 * Class for a model of parent
 */
class Parents extends Model {
    /**
     * Get the table that corresponds to the model
     * 
     * @return the table that corresponds to the model
     */
    public function getSource() {
        return 'parent';
    }
    
    /**
     * Sets the relations between the models
     */
    public function initialize() {
        $this->belongsTo("address_id", "Addresses", "id", array("alias" => "Address"));
        $this->belongsTo("registration_id", "Registrations", "id", array("alias" => "Parents"));
    }

}
