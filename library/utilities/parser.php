<?php

class Parser {

    /**
     * Parse time from ##:## format to ##:##:##
     *
     * @param String  $time  A string containing a time in a ##:## format
     * 
     * @return String  Parsed time
     */
    static public function parseTime($time) {
        return DateTime::createFromFormat('H:i', $time)->format('H:i:s');
    }

}
