<?php

use Phalcon\Mvc\Model;

/**
 * Class for a model of camp
 */
class Camps extends Model {

    /**
     * Get the table that corresponds to the model
     *
     * @return the table that corresponds to the model
     */
    public function getSource() {
        return 'camp';
    }

}
