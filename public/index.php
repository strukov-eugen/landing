<?php
// Get the request URI
$requestUri = $_SERVER['REQUEST_URI'];

// Remove GET parameters from the URI (if any)
$requestUri = strtok($requestUri, '?');

// Parse the request path
$segments = explode('/', trim($requestUri, '/'));

// If it's the root of the site (or just "/"), serve the landing page
if ($requestUri === '' || $requestUri === '/') {
    include_once '../public/index.html';
    exit;
}

// Determine the API version (default is v1)
$apiVersion = isset($segments[1]) ? $segments[1] : 'v1';

// Check if the user is accessing Swagger UI
if ($segments[0] === 'swagger') {
    // Include Swagger UI, passing the path to the appropriate swagger.json
    include_once '../public/swagger/index.html';
    exit;
}

// Build the path to the API
$apiPath = "../api/{$apiVersion}/index.php";

// Check if the API file for the specified version exists
if (file_exists($apiPath)) {
    require_once $apiPath;
} else {
    // If the version is not found, return an error
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'API version not found']);
}

