<?php


namespace App;


use App\Classes\Core;

class AppCore extends Core
{

    public function before()
    {
        // Set page for error 404
/*        $this->router->notFount(function () {
            echo '
                <link rel="stylesheet" href="/css/grid.css">
                <div class="table height-100 text-center color-red">
                    <div>
                        <h1>ERROR 404</h1>
                        <h1>Page not found!</h1>
                    </div>
                </div>';
        });*/
    }

    public function after(){}

    public function error($error)
    {
        var_dump( $error );
    }

    public function middleware()
    {
        return [
            \App\Middleware\AuthMiddleware::class,
            \App\Middleware\TokenMiddleware::class,
        ];
    }

    function services()
    {
        $route = $this->router->getResult();
        $services = [];

var_dump( $route );

        $services = [
            \App\Services\Database\DatabaseService::class,
            \App\Services\ClientData\ClientDataService::class,
        ];

        if ($route === '/') {
            $services[] = \App\Services\FilesystemService::class;
        }

        return $services;
    }
}