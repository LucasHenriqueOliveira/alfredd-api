<?php
return [
    'mysql' => [
        'driver' => env('DB_CONNECTION', ''),
        'host' => env('DB_HOST', ''),
        'port' => env('DB_PORT', ''),
        'database' => env('DB_DATABASE', ''),
        'service_name' => env('DB_SERVICE_NAME', ''),
        'username' => env('DB_USERNAME', ''),
        'password' => env('DB_PASSWORD', ''),
        'charset' => env('DB_CHARSET', ''),
        'prefix' => env('DB_PREFIX', ''),
    ]
];