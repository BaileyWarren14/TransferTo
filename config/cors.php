<?php

return [
    'paths' => ['api/*', 'sb-json'],

    'allowed_methods' => ['GET', 'POST'],

    'allowed_origins' => [
        'https://tudominio.com',      // Producción
        'http://localhost:3000',      // Desarrollo con React/Vue/etc
        'http://127.0.0.1:8000',      // Desarrollo con Laravel local
        'http://192.168.*',           // Cualquier IP en tu red local
    ],

    'allowed_headers' => ['*'],

    'supports_credentials' => false,
];