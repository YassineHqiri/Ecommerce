<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    'allowed_origins' => ['http://localhost:5173', 'http://localhost:3000', 'https://atlastech.com', 'https://www.atlastech.com'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['X-CSRF-TOKEN', 'X-Requested-With', 'Content-Type', 'Accept', 'Authorization'],
    'exposed_headers' => [],
    'max_age' => 86400,
    'supports_credentials' => true,
];
