<?php

return [

    'get' => [
        '/' => 'App\\Controllers\\MainController@index',
        '/news' => 'App\\Controllers\\MainController@news',
        '/blog' => 'App\\Controllers\\MainController@blog',
        '/contacts' => 'App\\Controllers\\MainController@contacts',
    ],

    'post' => [
        '/api' => 'App\\Controllers\\Api\\RPCController@index',
    ],

];
