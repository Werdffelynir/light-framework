# Class Router


```php

$router = new \App\Classes\Router([
    'domain' => 'domain.com',
    'path' => '/',
]);

$router->get('/' , function () {
    echo 'main';
});

$router->get('/user/{n?}' , function ($id) {
    echo 'user';
});

$router->notFount(function () {
    echo 'notFount';
});

$router->run();

```
