<?php

require 'vendor/autoload.php';
include 'bootstrap.php';

use  Chatter\Models\Message;

$app = new \Slim\App();

$app->get('/messages', function ($request, $response, $args) {
    $_message = new Message();
    $messages = $_message->all();
    $payload = [];
    foreach ($messages as $_msg) {
        $payload[$_msg->id] = [
            'body' => $_msg->body,
            'user_id' => $_msg->user_id,
            'created_at' => $_msg->created_at
        ];
    }

    return $response->withStatus(200)->withJson($payload);
});

$app->run();