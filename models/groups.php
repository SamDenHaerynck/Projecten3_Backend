<?php

use Phalcon\Mvc\Model;

/**
 * Class for a model of group
 */
class Groups extends Model {
    /**
     * Get the table that corresponds to the model
     * 
     * @return the table that corresponds to the model
     */
    public function getSource() {
        return 'group';
    }


}
