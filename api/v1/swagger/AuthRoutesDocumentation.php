<?php

namespace PromptBuilder\swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'My API',
    version: '1.0.0',
    description: 'Описание вашего API для работы c пользователями и аутентификацией.'
)]
#[OA\PathItem(
    path: '/api/v1'
)]
#[OA\SecurityScheme(
    securityScheme: 'BearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'JWT аутентификация'
)]
class AuthRoutesDocumentation
{
    // Атрибуты для маршрута /send-code
    #[OA\Post(
        path: '/api/v1/auth/send-code',
        summary: 'Отправка OTP-кода',
        description: 'Отправляет OTP-код на указанный номер телефона через внешний API.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['phone', 'evinaRequestId'],
                    properties: [
                        new OA\Property(property: 'phone', type: 'string', example: '971500000000'),
                        new OA\Property(property: 'evinaRequestId', type: 'string', example: '123e4567-e89b-12d3-a456-426614174000'),
                        new OA\Property(property: 'clickid', type: 'string', example: 'CLICKID123', nullable: true),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'OTP успешно отправлен'),
            new OA\Response(response: 400, description: 'Ошибка запроса'),
            new OA\Response(response: 500, description: 'Ошибка сервера')
        ]
    )]
    public function sendCode(): void
    {
        // Пустой метод для Swagger
    }

    // Атрибуты для маршрута /verify-code
    #[OA\Post(
        path: '/api/v1/auth/verify-code',
        summary: 'Верификация OTP-кода',
        description: 'Проверяет введённый пользователем OTP-код через внешний API.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['phone', 'code', 'sessionId', 'evinaRequestId'],
                    properties: [
                        new OA\Property(property: 'phone', type: 'string', example: '971500000000'),
                        new OA\Property(property: 'code', type: 'string', example: '1234'),
                        new OA\Property(property: 'sessionId', type: 'string', example: 'abc123reqid'),
                        new OA\Property(property: 'evinaRequestId', type: 'string', example: '123e4567-e89b-12d3-a456-426614174000'),
                        new OA\Property(property: 'clickid', type: 'string', example: 'CLICKID123', nullable: true),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'OTP успешно верифицирован'),
            new OA\Response(response: 400, description: 'Неверный код или данные'),
            new OA\Response(response: 500, description: 'Ошибка сервера')
        ]
    )]
    public function verifyCode(): void
    {
        // Пустой метод для Swagger
    }
    
}
