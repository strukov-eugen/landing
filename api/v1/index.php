<?php

namespace PromptBuilder;

use PromptBuilder\core\App;
//use PromptBuilder\middlewares\AuthMiddleware;
use PromptBuilder\middlewares\CorsMiddleware;
use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

// Загружаем переменные окружения из .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Создаем экземпляр приложения
$app = new App();

$app->use(new CorsMiddleware());

// Регистрируем маршрут для аутентификации
$app->use('/api/v1/auth', 'routes/authRoutes.php');

// Регистрируем middleware для авторизации
//$app->use(new AuthMiddleware());

// Обрабатываем запрос
$app->handleRequest();

