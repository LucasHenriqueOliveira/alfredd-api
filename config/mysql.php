<?php
return [
    'mysql' => [
        'driver' => env('DB_CONNECTION', ''),
        'host' => env('DB_HOST', ''),
        'port' => env('DB_PORT', ''),
        'database' => env('DB_DATABASE', ''),
        'username' => env('DB_USERNAME', ''),
        'password' => env('DB_PASSWORD', ''),
        'charset' => env('DB_CHARSET', ''),
        'collation' => env('DB_COLLATION', ''),
    ]
];