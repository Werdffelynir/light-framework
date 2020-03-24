<?php


namespace App\Classes;


class Response
{
    private $data = [];

    public function data ($key, $value = null)
    {
        if ($value)
            return $this->data[$key] = $value;
        return isset($this->send[$key]) ? $this->data[$key] : null;
    }

    public function clear ()
    {
        $this->data = [];
    }

    public function json (array $values = null)
    {
        $data = $this->data;

        if ($values) {
            $data = array_merge($data, $values);
        }

        return json_encode($data);
    }

    public function redirect ($url)
    {
        header("Location: /$url");
    }
}