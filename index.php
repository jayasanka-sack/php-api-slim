<?php

require 'vendor/autoload.php';
include 'bootstrap.php';

$app = new \Slim\App();

$app->get('/messages', function ($request, $response, $args){
    return $response->write("This is our list of messages");
});

$app->run();