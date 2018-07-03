<?php

require 'vendor/autoload.php';

$app = new \Slim\App();

$app->get('/hello/{name}', function ($request, $response, $args){
    return $response->write("hello ".$args['name']);
});

$app->run();