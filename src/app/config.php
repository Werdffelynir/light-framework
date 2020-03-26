<?php

const ROOT = __DIR__;

return [

    'app' => [
        'mode' => 'development',
    ],

    'db' => [
        'main' => 'firebird',

        'mysql' => [
            'host' => 'localhost',
            'port' => '3306',
            'user' => 'root',
            'pass' => '',
            'dbname' => 'database_name',
        ],

        'firebird' => [
            'host' => 'localhost',
            'port' => '',
            'user' => 'SYSDBA',
            'pass' => 'masterkey',
            'dbname' => 'localhost:/path/to/database/DATABASE_FILE.FDB',
        ],
    ],

    'router' => [
        'domain' => 'localhost',
        'path' => '/',
    ],

    'template' => [
        'path' => ROOT . '/views',
        'template' => 'layout/template.php'
    ],

    'log' => [
        'path' => ROOT . '/logs'
    ],

];
