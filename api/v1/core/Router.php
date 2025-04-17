<?php

namespace PromptBuilder\core;

class Router {
    private array $routes = [];
    private MiddlewareHandler $middlewareHandler;
    private string $basePath;

    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
        $this->middlewareHandler = new MiddlewareHandler();
    }

    public function get($route, $handler) {
        $this->addRoute('GET', $route, $handler);
    }

    public function post($route, $handler) {
        $this->addRoute('POST', $route, $handler);
    }

    public function put($route, $handler) {
        $this->addRoute('PUT', $route, $handler);
    }

    public function delete($route, $handler) {
        $this->addRoute('DELETE', $route, $handler);
    }

    private function addRoute($method, $route, $handler) {
        $this->routes[$method][$route] = $handler;
    }

    public function useMiddleware($middleware) {
        $this->middlewareHandler->addMiddleware($middleware);
    }

    public function handle($uri, $method, Request $request, Response $response) {
        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            if ($this->matchRoute($route, $uri, $params)) {
                list($controller, $methodName) = explode('@', $handler);

                if (class_exists($controller)) {

                    $container = AppContainer::getContainer();
                    // Контейнер сам создаст контроллер и передаст зависимости
                    $controllerInstance = $container->get($controller);

                    if (method_exists($controllerInstance, $methodName)) {
                        $request->setParams($params);
                        // Запуск цепочки Middleware
                        $this->middlewareHandler->handle($request, $response, function() use ($controllerInstance, $methodName, $request, $response) {
                            $controllerInstance->$methodName($request, $response);
                        });
                        return;
                    }
                }

                (new Response())->json(["message" => "Controller or method not found"], 500);
                return;
            }
        }

        (new Response())->json(["message" => "Route not found"], 404);
    }

    private function matchRoute($route, $uri, &$params) {
        // Убираем из URI базовый путь, если он существует
        $uri = preg_replace('#^' . preg_quote($this->basePath, '#') . '#', '', $uri);
        
        // Нормализуем слэши в конце
        $route = rtrim($route, '/');
        $uri = rtrim($uri, '/');

        // Строим паттерн маршрута, заменяя параметры в фигурных скобках на регулярные выражения
        $routePattern = preg_replace('/\{([\w]+)\}/', '(?P<$1>[^/]+)', $route);
        $routePattern = str_replace('/', '\/', $routePattern);
        
        // Проверяем, совпадает ли маршрут с URI
        if (preg_match('/^' . $routePattern . '$/', $uri, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }
        
        return false;
    }
}