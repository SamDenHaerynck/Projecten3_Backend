<?php

use Phalcon\Mvc\Model;

/**
 * Class for a model of participants
 */
class Pictures extends Model {

    /**
     * Get the table that corresponds to the model
     * 
     * @return the table that corresponds to the model
     */
    public function getSource() {
        return 'picture';
    }

}
