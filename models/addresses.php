<?php

use Phalcon\Mvc\Model;

/**
 * Class for a model of address
 */
class Addresses extends Model {

    /**
     * Get the table that corresponds to the model
     * 
     * @return the table that corresponds to the model
     */
    public function getSource() {
        return 'address';
    }

    /**
     * Sets the relations between the models
     */
    public function initialize() {
        $this->hasOne("id", "Registrations", "address_id", array("alias" => "Address"));
        $this->hasOne("id", "Parents", "address_id", array("alias" => "Address"));
    }

}
