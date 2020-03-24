<?php


namespace App\Classes;


use Throwable;

class TemplateException extends \Exception
{
    use RenderException;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $title = 'Framework "TemplateException": ';
        parent::__construct($title . $message, $code, $previous);
    }

}