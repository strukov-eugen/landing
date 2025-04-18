<?php

namespace PromptBuilder\swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'My API',
    version: '1.0.0',
    description: 'Description of your API for user management and authentication.'
)]
#[OA\PathItem(
    path: '/api/v1'
)]
#[OA\SecurityScheme(
    securityScheme: 'BearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'JWT authentication'
)]
class AuthRoutesDocumentation
{
    // Attributes for the /send-code route
    #[OA\Post(
        path: '/api/v1/auth/send-code',
        summary: 'Send OTP code',
        description: 'Sends an OTP code to the specified phone number via an external API.',
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
            new OA\Response(response: 200, description: 'OTP sent successfully'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 500, description: 'Server error')
        ]
    )]
    public function sendCode(): void
    {
        // Empty method for Swagger
    }

    // Attributes for the /verify-code route
    #[OA\Post(
        path: '/api/v1/auth/verify-code',
        summary: 'Verify OTP code',
        description: 'Verifies the OTP code entered by the user via an external API.',
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
            new OA\Response(response: 200, description: 'OTP successfully verified'),
            new OA\Response(response: 400, description: 'Invalid code or data'),
            new OA\Response(response: 500, description: 'Server error')
        ]
    )]
    public function verifyCode(): void
    {
        // Empty method for Swagger
    }

}
