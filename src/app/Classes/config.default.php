<?php

return [

    'app' => [
        'mode' => 'development',
    ],

    'db' => [

        'sqlite' => [
            'host' => 'localhost',
            'port' => null,
            'user' => null,
            'pass' => null,
            'dbname' => '/path/to/database/sqlite.sqlite',
        ],

        'mysql' => [
            'host' => 'localhost',
            'port' => '3306',
            'user' => 'root',
            'pass' => '',
            'dbname' => 'database_name',
        ],

        'firebird' => [
            'host' => 'localhost',
            'port' => null,
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
        'path' => '/views',
        'template' => 'layout/template.php'
    ],

    'mail' => [
        'host' => '',
        'port' => '',
        'user' => '',
        'password' => '',
    ],

    'log' => [
        'path' =>  './logs'
    ],

];







