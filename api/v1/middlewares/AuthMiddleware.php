<?php

namespace PromptBuilder\middlewares;

use PromptBuilder\core\Request;
use PromptBuilder\core\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    private string $jwtSecret;

    public function __construct()
    {
        $this->jwtSecret = $_ENV['JWT_SECRET'] ?? 'your_jwt_secret';
    }

    public function handle(Request $req, Response $res, $next)
    {
        // Get the token from the Authorization header
        $authorizationHeader = $req->getHeaders()['Authorization'] ?? 
            $req->getHeaders()['AUTHORIZATION'] ?? '';

        if (empty($authorizationHeader)) {
            $res->json(['message' => 'No token provided, authorization denied'], 401);
            return;
        }

        // Extract the token from the Authorization header
        $token = str_replace('Bearer ', '', $authorizationHeader);

        if (empty($token)) {
            $res->json(['message' => 'Token is missing, authorization denied'], 401);
            return;
        }

        try {
            // Decode the token using the secret key
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            // Add the user data to the request object
            $req->setUser((array)$decoded);
        } catch (\Exception $e) {
            $res->json(['message' => 'Token is not valid', 'error' => $e->getMessage()], 403);
            return;
        }

        // All checks passed, pass control to the next middleware
        $next();
    }
}
