<?php

use Phalcon\Mvc\Model;

/**
 * Class for a model of post
 */
class Posts extends Model {

    protected $title;
    protected $text;
    protected $location;
    protected $date;
    protected $startTime;
    protected $endTime;
    protected $postType;

    /**
     * Get the table that corresponds to the model
     * 
     * @return the table that corresponds to the model
     */
    public function getSource() {
        return 'post';
    }

    /**
     * Sets the relations between the models
     */
    public function initialize() {
        $this->belongsTo("user_id", "Users", "id", array("alias" => "User"));
        $this->hasMany("id", "Comments", "post_id", array("alias" => "Posts"));
    }

    public function setTitle($title) {
        if (Validator::isEmpty($title)) {
            echo "title";
            Errorhandler::throwRestException();
        }
        $this->title = Filter::filterText($title);
    }

    public function setText($text) {
        if (Validator::isEmpty($text)) {
            echo "text";
            Errorhandler::throwRestException();
        }
        $this->text = Filter::filterText($text);
    }

    public function setLocation($location) {
        $this->location = Filter::filterText($location);
    }

    public function setDate($date) {
        if (Validator::isEmpty($date)) {
            $this->date = "0000-00-00";
        } else {
            if (!Validator::isValidDate($date)) {
                echo "date";
                Errorhandler::throwRestException();
            }
            $this->date = $date;
        }
    }

    public function setStartTime($startTime) {
        if (!Validator::isEmpty($startTime)) {
            if (!Validator::isValidTime($startTime)) {
                Errorhandler::throwRestException();
            }
            $this->startTime = Parser::parseTime($startTime);
        }
    }

    public function setEndTime($endtime) {
        if (!Validator::isEmpty($endtime)) {
            if (!Validator::isValidTime($endtime)) {
                Errorhandler::throwRestException();
            }
            $this->startTime = Parser::parseTime($endtime);
        }
    }

    public function setPostType($postType) {
        if (Validator::isEmpty($postType)) {
            Errorhandler::throwRestException();
        }
        $this->postType = Filter::filterText($postType);
    }

    public function getTitle() {
        return $this->title;
    }

    public function getText() {
        return $this->text;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getDate() {
        return $this->date;
    }

    public function getStartTime() {
        return $this->startTime;
    }

    public function getEndTime() {
        return $this->endtime;
    }

}
