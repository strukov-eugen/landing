<?php

namespace Landing\middlewares;

use Landing\core\Request;
use Landing\core\Response;

class CorsMiddleware
{
    public function handle(Request $req, Response $res, $next)
    {
        // Set CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization');

        // If it's a preflight request (OPTIONS), end execution
        if ($req->getMethod() === 'OPTIONS') {
            http_response_code(204); // Нет контента
            exit;
        }

        // Pass control to the next middleware or route
        $next();
    }
}
