<?php

class Filter {

    /**
     * Strips htmltags and spaces from text
     *
     * @param String  $text  The text to filter
     * 
     * @return String  $newText  the stripped text
     */
    public static function filterText($text) {

        $filter = new \Phalcon\Filter();

        //Strip tags and spaces by using Phalcon filters
        $newText = $filter->sanitize($filter->sanitize($text, "striptags"), "trim");

        return $newText;
    }

}
