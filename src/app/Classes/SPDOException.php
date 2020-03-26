<?php


namespace App\Classes;


use Throwable;

class SPDOException extends \PDOException
{
    use RenderException;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $title = 'Framework "SPDOException": ';
        parent::__construct($title . $message, $code, $previous);
    }

}