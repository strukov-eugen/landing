<?php

namespace PromptBuilder\core;

class Request {
    private string $method;
    private string $uri;
    private array $params;
    private array $body;
    private array $headers = [];
    private array $user = [];

    public function __construct($method, $uri, $params = [])
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->params = $params;
        $this->body = json_decode(file_get_contents('php://input'), true) ?? [];

        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                // Преобразуем заголовки из формата HTTP_X в привычный вид
                $this->headers[str_replace('_', '-', substr($key, 5))] = $value;
            }
        }
    }

    public function getMethod() { return $this->method; }
    public function getUri() { return $this->uri; }
    public function getParams() { return $this->params; }
    public function setParams($params):void { $this->params = $params; }
    public function getBody() { return $this->body; }
    public function getHeaders() { return $this->headers; }
    public function getUser() { return $this->user; }
    public function setUser($decoded):void { $this->user = $decoded; }
    public function getQueryParams(): array { return $_GET; }

}
