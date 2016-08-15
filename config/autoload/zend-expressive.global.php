<?php

use App\Middleware\JsonErrorHandlerMiddleware;

return [
    'debug' => false,

    'config_cache_enabled' => false,
    
    'zend-expressive' => [
        'error_handler' => [
            'plugins' => [
                    'factory' => [
                        'application/json' => JsonErrorHandlerMiddleware::class,
                    ],
                    'aliases' => [
                        'application/x-json' => 'application/json',
                        'text/json' => 'application/json',
                    ],
                ],
        ]
    ],
];
