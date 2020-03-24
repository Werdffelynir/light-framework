<?php

const ROOT = __DIR__;

return [

    'app' => [
        'mode' => 'development',
        'domain' => 'localhost',
        'path' => '/',
    ],

    'db' => [
        'mysql' => [
            'host' => 'localhost',
            'port' => '3306',
            'user' => 'root',
            'pass' => '',
        ],
    ],

    'template' => [
        'path' => ROOT . '/views',
        'template' => 'layout/template.php'
    ],

    'log' => [
        'path' => ROOT . '/logs'
    ],

];
