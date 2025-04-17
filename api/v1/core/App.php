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

    // Метод для добавления маршрутов или middleware
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

    // Метод для обработки запроса
    public function handleRequest()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->handlers as $handler) {
            if ($handler['type'] === 'middleware') {
                // Предполагаем, что $handler['handler'] — это как функция, так и объект класса
                if (is_object($handler['handler'])) {
                    // Это объект с методом handle
                    $this->middlewareHandler->addMiddleware(function ($req, $res, $next) use ($handler) {
                        // Проверяем, что у объекта есть метод handle
                        if (method_exists($handler['handler'], 'handle')) {
                            // Вызываем метод handle у объекта
                            $handler['handler']->handle($req, $res, $next);
                        }
                    });
                } else {
                    // Если это функция или замыкание
                    $this->middlewareHandler->addMiddleware($handler['handler']);
                }
            }
            // Если это маршрут, проверяем его
            elseif ($handler['type'] === 'route' && $this->matchRoute($handler['route'], $uri)) {

                // Создаем экземпляры Request и Response только один раз
                $request = new Request($method, $uri);
                $response = new Response();

                // Передаем управление в Router после выполнения всех middleware
                $this->middlewareHandler->handle($request, $response, function() use ($uri, $method, $handler, $request, $response) {
                    $router = new Router($handler['route']);
                    // Загружаем файл маршрута
                    require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $handler['filePath'];
                    $router->handle($uri, $method, $request, $response); // Обрабатываем маршрут
                });
                return;
            }
        }

        // Если маршрут не найден, возвращаем 404
        http_response_code(404);
        echo json_encode(["message" => "Not Found"]);
    }

    // Метод для проверки совпадения маршрута с URI
    private function matchRoute($route, $uri)
    {
        $check = preg_match('#^' . preg_quote($route, '#') . '(/.*)?$#', $uri);
        return $check;
    }
}