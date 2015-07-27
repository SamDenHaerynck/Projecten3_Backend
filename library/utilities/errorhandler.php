<?php

class Errorhandler {

    /**
     * Throw new RestException
     * 
     * @return Response $response  {"status":"error"}
     */
    public static function throwRestException() {
        $response = new Phalcon\Http\Response();

        $response->setJsonContent(array(
            'status' => 'error'
        ));

        $response->send();
        die();
    }

}
