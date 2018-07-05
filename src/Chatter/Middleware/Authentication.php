<?php

namespace Chatter\Middleware;

use Chatter\Models\User;

class Authentication
{

    public function __invoke($request, $response, $next)
    {
        $auth = $request->getHeader('Authorization');
        $_apikey = $auth[0];
        $apikey = substr($_apikey, strpos($_apikey, ' '));
        $apikey = trim($apikey);

        $user = new User();

        if (!$user->authenticate($apikey)) {
            $response->withStatus(401);

            return $response;
        }

        $request = $request->withAttribute('user_id', $user->details->id);
        $response = $next($request, $response);

        return $response;
    }
}