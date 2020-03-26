<?php


namespace App;


use App\Classes\Core;

class AppCore extends Core
{

    public function before()
    {
        // Set default template views to positions
        $this->template->setPosition('main', 'login');
        $this->template->setPosition('sidebar', 'sidebar');

        // Set default page title
        $this->template->variable('title', 'Light PHP Framework');
    }

    public function after()
    {
        // Set page for error 404
        $this->router->notFount(function () {
            echo $this->template->render('404');
        });
    }

    public function error($error)
    {
        // Output error
        $this->router->notFount(function () use ($error) {
            echo $this->template->render('404', [
                'error' => 'Houston we have problems:' . print_r($error, true)
            ]);
        });
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
        $path = $this->router->getPath();
        $services = [];

        $services = [
            \App\Services\Database\DatabaseService::class,
            \App\Services\ClientData\ClientDataService::class,
        ];

        if ($path === '/') {
            $services[] = \App\Services\FilesystemService::class;
        }

        return $services;
    }
}
