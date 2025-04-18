<?php

namespace PromptBuilder\core;

class MiddlewareHandler
{
    private array $middlewares = [];
    private int $index = 0;

    // Add middleware to the chain
    public function addMiddleware($middleware)
    {
        $this->middlewares[] = $middleware;
    }

    // Process middleware and pass control to the next one
    public function handle(Request $req, Response $res, $next)
    {
        if ($this->index < count($this->middlewares)) {
            $middleware = $this->middlewares[$this->index];
            $this->index++;

            // If the middleware is an object with a handle method
            if (is_object($middleware) && method_exists($middleware, 'handle')) {
                $middleware->handle($req, $res, function() use ($req, $res, $next) {
                    $this->handle($req, $res, $next);  // Recursively pass control
                });
            }
            // If the middleware is a closure
            elseif (is_callable($middleware)) {
                $middleware($req, $res, function() use ($req, $res, $next) {
                    $this->handle($req, $res, $next);  // Recursively pass control
                });
            }
        } else {
            // If all middleware have been processed, pass control to the controller
            $next();
        }
    }
}