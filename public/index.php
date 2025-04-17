<?php
// Получаем URI запроса
$requestUri = $_SERVER['REQUEST_URI'];

// Убираем GET-параметры из URI (если есть)
$requestUri = strtok($requestUri, '?');

// Разбираем путь запроса
$segments = explode('/', trim($requestUri, '/'));

// Если корень сайта (или просто "/"), отдаем лендинг
if ($requestUri === '' || $requestUri === '/') {
    include_once '../public/index.html';
    exit;
}

// Определяем версию API (по умолчанию v1)
$apiVersion = isset($segments[1]) ? $segments[1] : 'v1';

// Проверяем, если пользователь открывает Swagger UI
if ($segments[0] === 'swagger') {
    // Подключаем Swagger UI, передавая путь к нужному swagger.json
    include_once '../public/swagger/index.html';
    exit;
}

// Формируем путь к API
$apiPath = "../api/{$apiVersion}/index.php";

// Проверяем, существует ли файл API для указанной версии
if (file_exists($apiPath)) {
    require_once $apiPath;
} else {
    // Если версия не найдена, возвращаем ошибку
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'API version not found']);
}

