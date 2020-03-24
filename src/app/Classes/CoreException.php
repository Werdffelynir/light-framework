<?php


namespace App\Classes;


use Throwable;

class CoreException extends \Exception
{
    use RenderException;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $title = 'Framework "CoreException": ';
        parent::__construct($title . $message, $code, $previous);
    }

}