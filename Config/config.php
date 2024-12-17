<?php
return [
    'database' => [
        'driver' => $_ENV['DB_DRIVER'], // mysql ou sqlite
        'host' => $_ENV['DB_HOST'] ?? null,
        'name' => $_ENV['DB_NAME'] ?? null,
        'user' => $_ENV['DB_USER'] ?? null,
        'password' => $_ENV['DB_PASS'] ?? null,
        'sqlite_path' => $_ENV['SQLITE_PATH'] ?? null,
    ],
];