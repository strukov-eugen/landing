<?php

namespace PromptBuilder\core;

class MiddlewareHandler
{
    private array $middlewares = [];
    private int $index = 0;

    // Добавляем middleware в цепочку
    public function addMiddleware($middleware)
    {
        $this->middlewares[] = $middleware;
    }

    // Обрабатываем middleware и передаем управление дальше
    public function handle(Request $req, Response $res, $next)
    {
        if ($this->index < count($this->middlewares)) {
            $middleware = $this->middlewares[$this->index];
            $this->index++;

            // Вызываем текущий middleware и передаем выполнение в следующий
            // Если middleware — объект с методом handle
            if (is_object($middleware) && method_exists($middleware, 'handle')) {
                $middleware->handle($req, $res, function() use ($req, $res, $next) {
                    $this->handle($req, $res, $next);  // Рекурсивно передаем управление
                });
            }
            // Если middleware — это замыкание (closure)
            elseif (is_callable($middleware)) {
                $middleware($req, $res, function() use ($req, $res, $next) {
                    $this->handle($req, $res, $next);  // Рекурсивно передаем управление
                });
            }
        } else {
            // Если все middleware обработаны, передаем выполнение в контроллер
            $next();
        }
    }
}