<?php

namespace Landing;

use Landing\core\App;
//use Landing\middlewares\AuthMiddleware;
use Landing\middlewares\CorsMiddleware;
use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables from .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Create an instance of the application
$app = new App();

$app->use(new CorsMiddleware());

// Register route for authentication
$app->use('/api/v1/auth', 'routes/authRoutes.php');

// Register middleware for authorization
//$app->use(new AuthMiddleware());

// Handle the request
$app->handleRequest();

