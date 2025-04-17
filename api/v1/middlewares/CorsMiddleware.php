<?php

namespace PromptBuilder\middlewares;

use PromptBuilder\core\Request;
use PromptBuilder\core\Response;

class CorsMiddleware
{
    public function handle(Request $req, Response $res, $next)
    {
        // Устанавливаем заголовки CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization');

        // Если это preflight-запрос (OPTIONS), просто завершаем выполнение
        if ($req->getMethod() === 'OPTIONS') {
            http_response_code(204); // Нет контента
            exit;
        }

        // Передаем управление следующему middleware или маршруту
        $next();
    }
}
