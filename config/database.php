<?php

$driver = getenv('DB_DRIVER') ?: 'json';

return [
    'driver' => $driver,
    'host' => getenv('DB_HOST') ?: 'localhost:3306',
    'dbname' => getenv('DB_NAME') ?: 'zap1380365_',
    'username' => getenv('DB_USER') ?: 'fhsadam',
    'password' => getenv('DB_PASS') ?: 'Fhsadam123!',
    'charset' => 'utf8mb4',
    'sqlite_path' => __DIR__ . '/../storage/napfeny.sqlite',
    'json_path' => __DIR__ . '/../storage/database.json',
];
