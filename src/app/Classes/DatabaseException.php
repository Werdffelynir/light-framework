<?php


namespace App\Classes;


use Throwable;

class DatabaseException extends \Exception
{
    use RenderException;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $title = 'Framework "DatabaseException": ';
        parent::__construct($title . $message, $code, $previous);
    }

}