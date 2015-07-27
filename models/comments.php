<?php

use Phalcon\Mvc\Model;

/**
 * Class for a model of comment
 */
class Comments extends Model {

    protected $text;

    /**
     * Get the table that corresponds to the model
     * 
     * @return the table that corresponds to the model
     */
    public function getSource() {
        return 'comment';
    }

    /**
     * Sets the relations between the models
     */
    public function initialize() {
        $this->belongsTo("post_id", "Posts", "id", array("alias" => "Posts"));
        $this->belongsTo("user_id", "Users", "id", array("alias" => "User"));
    }

    public function setText($text) {
        if (Validator::isEmpty($text)) {
            Errorhandler::throwRestException();
        }
        $this->text = Filter::filterText($text);
    }

}
