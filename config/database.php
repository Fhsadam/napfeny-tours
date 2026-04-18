<?php

$driver = getenv('DB_DRIVER') ?: 'mysql';

return [
    'driver' => $driver,
    'host' => getenv('DB_HOST') ?: 'localhost',
    'dbname' => getenv('DB_NAME') ?: 'utazas',
    'username' => getenv('DB_USER') ?: 'fhsdsql',
    'password' => getenv('DB_PASS') ?: 'FhsDWeb123.',
    'charset' => 'utf8mb4',
    'sqlite_path' => __DIR__ . '/../storage/napfeny.sqlite',
    'json_path' => __DIR__ . '/../storage/database.json',
];
