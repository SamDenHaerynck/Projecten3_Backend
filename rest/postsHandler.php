<?php

require APP_AUTH . "/authenticate.php";
require APP_UTILITIES . "/validator.php";
require APP_UTILITIES . "/errorhandler.php";
require APP_UTILITIES . "/filters.php";
require APP_UTILITIES . "/parser.php";
require APP_UTILITIES . "/functions.php";
require APP_UTILITIES . "/response.php";

//Autoload RestFunctions object
$restFunctions = new RestFunctions();

$app->before(function() use ($app) {
    Auth::isAuthorized($app, false);
});

//URI to get posts
$app->get('/api/posts', function() use ($app, $restFunctions) {
    $phql = "SELECT p.id, timestamp, title, date, postType, firstname, lastname"
            . " FROM Posts p INNER JOIN Users u ON p.user_id = u.id";
    HttpResponse::showJsonReponse($restFunctions->getListOfObjects($app, $phql));
});

//URI to get a post
$app->get('/api/posts/{id:[0-9]+}', function($id) use ($app, $restFunctions) {
    $phql = "SELECT p.id, timestamp, title, text, location, date, startTime, endTime, postType, firstname, lastname"
            . " FROM Posts p"
            . " INNER JOIN Users u ON p.user_id = u.id"
            . " WHERE p.id = :id:";
    HttpResponse::showJsonReponse($restFunctions->getSingleObject($app, $phql, $id));
});

//URI to get comments of a post
$app->get('/api/posts/{id:[0-9]+}/comments', function($id) use ($app, $restFunctions) {
    $phql = "SELECT c.id, c.timestamp, c.text, u.firstname, u.lastname"
            . " FROM Comments c "
            . " INNER JOIN Posts p ON c.post_id = p.id"
            . " INNER JOIN Users u ON c.user_id = u.id "
            . " WHERE c.post_id = $id";
    HttpResponse::showJsonReponse($restFunctions->getListOfObjects($app, $phql));
});

//URI to submit a post
$app->post('/api/posts/submit', function() use ($app) {
    $jsonData = Functions::getJsonData($app);

    $post = new Posts();

    $post->timestamp = Functions::getCurrentDateTime();
    $post->setTitle($jsonData['title']);
    $post->setText($jsonData['text']);
    $post->setLocation($jsonData['location']);
    $post->setDate($jsonData['date']);
    $post->setStartTime($jsonData['startTime']);
    $post->setEndTime($jsonData['endTime']);
    $post->setPostType($jsonData['postType']);

    $user = Auth::getUser($app);
    $post->user = $user;

    $post->save();
});

//URI to submit a comment to a post
$app->post('/api/posts/{id:[0-9]+}/comments/submit', function($id) use ($app) {
    echo $app->request->getRawBody();
    $jsonData = Functions::getJsonData($app);

    $comment = new Comments();

    $comment->timestamp = Functions::getCurrentDateTime();
    $comment->setText($jsonData['text']);

    $post = Posts::findFirst("id = '" . $id . "'");
    $comment->posts = $post;

    $user = Auth::getUser($app);
    $comment->user = $user;

    $comment->save();
});

//URI to send 200 OK to angular
$app->options('/api/posts', function() use ($app) {
    HttpResponse::showOk();
});
