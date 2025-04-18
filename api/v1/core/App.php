<?php

namespace PromptBuilder\core;

class App
{
    private array $handlers = [];
    private MiddlewareHandler $middlewareHandler;

    public function __construct()
    {
        $this->middlewareHandler = new MiddlewareHandler();
    }

    // Method to add routes or middleware
    public function use($routeOrMiddleware, $filePathOrHandler = null)
    {
        if (is_callable($routeOrMiddleware)) {
            $this->handlers[] = ['type' => 'middleware', 'handler' => $routeOrMiddleware];
        } elseif (is_object($routeOrMiddleware) && method_exists($routeOrMiddleware, 'handle')) {
            $this->handlers[] = ['type' => 'middleware', 'handler' => $routeOrMiddleware];
        }else {
            $this->handlers[] = ['type' => 'route','route' => $routeOrMiddleware,
                'filePath' => $filePathOrHandler
            ];
        }
    }

    // Method to handle the request
    public function handleRequest()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->handlers as $handler) {
            if ($handler['type'] === 'middleware') {
                // Assuming $handler['handler'] is either a function or a class object
                if (is_object($handler['handler'])) {
                    // If it's an object with a handle method
                    $this->middlewareHandler->addMiddleware(function ($req, $res, $next) use ($handler) {
                        // Check if the object has a handle method
                        if (method_exists($handler['handler'], 'handle')) {
                            // Call the handle method of the object
                            $handler['handler']->handle($req, $res, $next);
                        }
                    });
                } else {
                    // If it's a function or closure
                    $this->middlewareHandler->addMiddleware($handler['handler']);
                }
            }
            // If it's a route, check it
            elseif ($handler['type'] === 'route' && $this->matchRoute($handler['route'], $uri)) {

                // Create Request and Response instances only once
                $request = new Request($method, $uri);
                $response = new Response();

                // Pass control to Router after executing all middleware
                $this->middlewareHandler->handle($request, $response, function() use ($uri, $method, $handler, $request, $response) {
                    $router = new Router($handler['route']);
                    // Load the route file
                    require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $handler['filePath'];
                    $router->handle($uri, $method, $request, $response); // Handle the route
                });
                return;
            }
        }

        // If route is not found, return 404
        http_response_code(404);
        echo json_encode(["message" => "Not Found"]);
    }

    // Method to check if the route matches the URI
    private function matchRoute($route, $uri)
    {
        $check = preg_match('#^' . preg_quote($route, '#') . '(/.*)?$#', $uri);
        return $check;
    }
}