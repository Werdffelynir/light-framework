<?php


namespace App\Classes;


use App\Classes\CoreException;
use App\Classes\TemplateException;

/**
 * Framework utils function
 */
include_once __DIR__ . '/utils.php';

/**
 * Class Core
 * @package App\Classes
 */
class Core
{
    /** @type Config */
    protected $config;

    /** @type Router */
    protected $router;

    /** @type Response */
    protected $response;

    /** @type Request */
    protected $request;

    /** @type Template */
    protected $template;

    protected $listMiddleware = [];
    protected $listServices = [];

    function __construct($config, $routers)
    {
        $this->config = new Config($config);

        try {
            $this->template = new Template(Config::get('template'));
        } catch (TemplateException $error) {
            $error->render();
        }
        $this->response = new Response();
        $this->request = new Request();


        $this->router = new Router(Config::get('router'));

        $this->routersHandler($routers);
    }

    protected function routersHandler($routers)
    {

        $this->listMiddleware = is_array($this->middleware()) ? $this->middleware() : [];
        $this->listServices = is_array($this->services()) ? $this->services() : [];

        for ($i = 0; $i < count($this->listMiddleware); $i++) {
            if (class_exists($this->listMiddleware[$i])) {
                $class = $this->listMiddleware[$i] = new $this->listMiddleware[$i]();
                $class->response = $this->response;
                $class->request = $this->request;
                $class->router = $this->router;
                if (method_exists($class, 'init')) {
                    $class->init();
                }
            }
        }

        if (method_exists($this, 'before')) $this->{'before'}();

        foreach ($routers as $method => $routes) {
            foreach ($routes as $route => $callback) {
                try {
                    $this->createController($method, $route, $callback);
                } catch (CoreException $error) {
                    $error->render();
                }
            }
        }

        $this->router->run();

        if (method_exists($this, 'after')) $this->{'after'}();

        for ($i = 0; $i < count($this->listServices); $i++) {
            if (class_exists($this->listServices[$i])) {
                $class = $this->listServices[$i] = new $this->listServices[$i]();
                if (method_exists($class, 'init')) {
                    $class->init();
                }
            }
        }

        if ($error = $this->router->getError()) {
            if (method_exists($this, 'error')) {
                $this->{'error'}($error);
            }
        }
    }

    protected function middleware()
    {
        return [];
    }

    protected function services()
    {
        return [];
    }

    /**
     * @param $method
     * @param $route
     * @param $callback
     * @throws \App\Classes\CoreException
     */
    private function createController($method, $route, $callback)
    {
        if (is_string($callback)) {
            $className = null;
            $methodName = null;
            $classInstance = null;
            $callbacks = explode('@', $callback);

            if (count($callbacks) === 2) {
                $className = isset($callbacks[0]) ? trim($callbacks[0]) : null;
                $methodName = isset($callbacks[1]) ? trim($callbacks[1]) : null;
            }

            if (class_exists($className)) {
                $classInstance = new $className();

                $classInstance->response = $this->response;
                $classInstance->request = $this->request;
                $classInstance->template = $this->template;

                if (!method_exists($classInstance, $methodName)) {
                    $methodName = 'index';
                }
                if (method_exists($classInstance, 'init')) {
                    $classInstance->init();
                }

                $callback = [$classInstance, $methodName];
            } else {
                throw new CoreException('Parameter is not callable classname: "' . (print_r($callback, true)) . '" ');
            }

        } else if (!is_callable($callback)) {
            throw new CoreException('Parameter is not callable: "' . $callback . '" ');
        }

        $this->router->map($method, $route, $callback);
    }

}