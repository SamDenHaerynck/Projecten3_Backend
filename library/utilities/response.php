<?php

class HttpResponse {

    /**
     * Send json response containing data back to client
     *
     * @param String  $data  Array containing data to be displayed
     *
     * @return Response $response  JSON data
     */
    public static function showJsonReponse($data) {
        $response = new Phalcon\Http\Response();
        $response->setJsonContent($data);
        $response->send();
    }

    /**
     * Send status 200 OK response to client
     *
     * @return Response $response  Status 200 OK
     */
    public static function showOk() {
        $response = new Phalcon\Http\Response();
        $response->setStatusCode(200, "OK");
        $response->send();
    }

    /**
     * Send status not found to client
     *
     * @return Response $response  {"status":"NOT-FOUND"}
     */
    public static function showJsonNotFound() {
        $response = new Phalcon\Http\Response();
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
        $response->send();
    }

}
