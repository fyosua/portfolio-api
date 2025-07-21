<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;

final class JwtDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        // Schema for login request
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => ['type' => 'string', 'example' => 'admin@yosuaf.com'],
                'password' => ['type' => 'string', 'example' => 'password'],
            ],
        ]);

        // Schema for login response
        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => ['token' => ['type' => 'string', 'readOnly' => true]],
        ]);

        // Schema for change password request
        $schemas['PasswordChange'] = new \ArrayObject([
            'type' => 'object',
            'properties' => ['password' => ['type' => 'string', 'example' => 'a-new-strong-password']],
        ]);

        // This is the corrected way to add security schemes for your version
        $components = $openApi->getComponents();
        $securitySchemes = $components->getSecuritySchemes() ?? [];
        $securitySchemes['bearerAuth'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ]);
        $openApi = $openApi->withComponents($components->withSecuritySchemes($securitySchemes));


        // Manually define the login endpoint
        $loginPathItem = new Model\PathItem(
            ref: 'Authentication',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['User & Authentication'],
                summary: 'Get a JWT token to authenticate.',
                requestBody: new Model\RequestBody(
                    description: 'Generate new JWT Token',
                    content: new \ArrayObject(['application/json' => ['schema' => ['$ref' => '#/components/schemas/Credentials']]])
                ),
                responses: [
                    '200' => [
                        'description' => 'Get JWT token',
                        'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/Token']]],
                    ],
                ]
            )
        );
        $openApi->getPaths()->addPath('/api/login', $loginPathItem);

        // Manually define the register endpoint
        $registerPathItem = new Model\PathItem(
            ref: 'Authentication',
            post: new Model\Operation(
                operationId: 'postRegisterItem',
                tags: ['User & Authentication'],
                summary: 'Register a new admin user. (Protected)',
                security: [['bearerAuth' => []]],
                requestBody: new Model\RequestBody(
                    description: 'New user credentials',
                    content: new \ArrayObject(['application/json' => ['schema' => ['$ref' => '#/components/schemas/Credentials']]])
                ),
                responses: ['201' => ['description' => 'User created successfully']]
            )
        );
        $openApi->getPaths()->addPath('/api/register', $registerPathItem);

        // Manually define the change password endpoint
        $changePasswordPathItem = new Model\PathItem(
            ref: 'Authentication',
            patch: new Model\Operation(
                operationId: 'patchUserPasswordItem',
                tags: ['User & Authentication'],
                summary: 'Change a user\'s password. (Protected)',
                security: [['bearerAuth' => []]],
                parameters: [new Model\Parameter('id', 'path', 'User identifier', true)],
                requestBody: new Model\RequestBody(
                    description: 'The new password',
                    content: new \ArrayObject(['application/json' => ['schema' => ['$ref' => '#/components/schemas/PasswordChange']]])
                ),
                responses: ['200' => ['description' => 'Password updated successfully']]
            )
        );
        $openApi->getPaths()->addPath('/api/users/{id}', $changePasswordPathItem);

        // Update the existing /api/users GET endpoint to be in the correct group
        $paths = $openApi->getPaths();
        $userListPath = $paths->getPath('/api/users');
        if ($userListPath) {
            $userListOperation = $userListPath->getGet()->withTags(['User & Authentication'])->withSecurity([['bearerAuth' => []]]);
            $paths->addPath('/api/users', $userListPath->withGet($userListOperation));
        }

        return $openApi;
    }
}
