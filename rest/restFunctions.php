<?php

class RestFunctions {

    /**
     * Retrieves data from database
     *
     * @param Micro  $app  The The Micro application
     * @param String  $phql  The PHQL query to execute
     * 
     * @return String[]  $data  Array containing all json data
     */
    public function getListOfObjects($app, $phql) {
        try {
            $objects = $app->modelsManager->executeQuery($phql);

            //Parse objects to an array
            $data = $objects->toArray();

            //Walk through array and UTF8 encode every item
            array_walk_recursive($data, function(&$item) {
                if (!mb_detect_encoding($item, 'utf-8', true)) {
                    $item = utf8_encode($item);
                }
            });

            return $data;
        } catch (Exception $ex) {
            Errorhandler::throwRestException();
        }
    }

    /**
     * Retrieves data from database
     *
     * @param Micro  $app  The The Micro application
     * @param String  $phql  The PHQL query to execute     
     * @param int  $id  The id of the row
     * 
     * @return String[]  $data  Array containing all json data
     */
    public function getSingleObject($app, $phql, $id) {
        try {
            //Execute query
            $obj = $app->modelsManager->executeQuery($phql, array(
                        'id' => $id
                    ))->getFirst();

            //If query fails display error
            if ($obj == false) {
                HttpResponse::showJsonNotFound();
            } else {
                //Parse objects to an array
                $data = array('status' => 'FOUND', 'data' => $obj->toArray());

                //Walk through array and UTF8 encode every item
                array_walk_recursive($data, function(&$item) {
                    if (!mb_detect_encoding($item, 'utf-8', true)) {
                        $item = utf8_encode($item);
                    }
                });
            }
            return $data;
        } catch (Exception $ex) {
            Errorhandler::throwRestException();
        }
    }

}
