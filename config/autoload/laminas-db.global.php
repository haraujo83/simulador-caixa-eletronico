<?php
declare(strict_types=1);

return [
    'db' => [
        'driver'   => 'pdo_mysql',
        'database' => 'cx',
        'username' => 'root',
        'password' => null,
        'hostname' => 'localhost',
        'charset'  => 'utf8mb4',
        'driver_options' => [
            PDO::ATTR_CASE              => PDO::CASE_LOWER,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS      => PDO::NULL_EMPTY_STRING,
        ],
    ],
];
