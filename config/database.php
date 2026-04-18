<?php

$driver = getenv('DB_DRIVER') ?: 'mysql';

return [
    'driver' => $driver,
    'host' => getenv('DB_HOST') ?: 'localhost',
    'dbname' => getenv('DB_NAME') ?: 'adatb',
    'username' => getenv('DB_USER') ?: 'adatbf',
    'password' => getenv('DB_PASS') ?: 'jelszo',
    'charset' => 'utf8mb4',
    'sqlite_path' => __DIR__ . '/../storage/napfeny.sqlite',
    'json_path' => __DIR__ . '/../storage/database.json',
];
