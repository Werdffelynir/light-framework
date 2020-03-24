<?php


namespace App\Classes;


class Request
{
    public function get ($key = false)
    {
        if ($key)
            return isset($_GET[$key]) ? $_GET[$key] : null;
        else
            return $_GET;
    }

    public function post ($key = false)
    {
        if ($key)
            return isset($_POST[$key]) ? $_POST[$key] : null;
        else
            return $_POST;
    }

    public function streamInput ($key = false)
    {
        $ctx = file_get_contents('php://input');

        if ($key)
            return isset($ctx[$key]) ? $ctx[$key] : null;
        else
            return $ctx;
    }

    public function json (array $json = null)
    {
        if ($json === null) {
            $json = $this->streamInput();
        }

        return json_decode($json, true);
    }

}