<?php

return [

    'app' => [
        'mode' => 'development',
        'domain' => 'localhost',
        'path' => '/',
    ],

    'db' => [

        'sqlite' => [
            'host' => 'sqlite:../database/sqlite.sqlite',
            'port' => null,
            'user' => 'root',
            'pass' => '',
        ],

        'mysql' => [
            'host' => 'localhost',
            'port' => '3306',
            'user' => 'root',
            'pass' => '',
        ],

        'firebird' => [
            'host' => 'firebird:../database/wtc.fdb',
            'port' => null,
            'user' => 'SYSDBA',
            'pass' => 'masterkey',
        ],
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







