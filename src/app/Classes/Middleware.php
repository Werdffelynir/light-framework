<?php


namespace App\Classes;


class Middleware
{
    /** @type Response */
    public $response;

    /** @type Request */
    public $request;

    /** @type Router */
    public $router;

    public function init () {}
}