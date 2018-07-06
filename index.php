<?php

require 'vendor/autoload.php';
include 'bootstrap.php';

use Chatter\Models\Message;
use Chatter\Middleware\Logging as ChatterLogging;
use Chatter\Middleware\Authentication as ChatterAuth;
use Chatter\Middleware\FileFilter;
use Chatter\Middleware\ImageRemoveExif;
use Chatter\Middleware\FileMove;

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);
$app->add(new ChatterAuth());
$app->add(new ChatterLogging());

$app->get('/messages', function ($request, $response, $args) {
    $_message = new Message();

    $messages = $_message->all();

    $payload = [];
    foreach($messages as $_msg) {
        $payload[$_msg->id] = ['body' => $_msg->body, 'user_id' => $_msg->user_id, 'created_at' => $_msg->created_at];
    }

    return $response->withStatus(200)->withJson($payload);
});

$filter = new FileFilter();
$removeExif = new ImageRemoveExif();
$move = new FileMove();

$app->post('/messages', function ($request, $response, $args) {
    $_message = $request->getParsedBodyParam('message', '');

    $imagepath = '';


    $message = new Message();
    $message->body = $_message;
    $message->user_id = -1;
    $message->image_url = $imagepath;
    $message->save();

    if ($message->id) {
        $payload = ['message_id' => $message->id, 'message_uri' => '/messages/' . $message->id];
        return $response->withStatus(201)->withJson($payload);
    } else {
        return $response->withStatus(400);
    }
})->add($filter)->add($removeExif)->add($move);

$app->delete('/messages/{message_id}', function ($request, $response, $args) {
   $message = Message::find($args['message_id']);
   $message->delete();

   if($message->exists){
       return $response->withStatus(400);
   }else{
        return $response->withStatus(204);
   }
});

// Run app
$app->run();