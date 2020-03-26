<?php


namespace App\Classes;


class Model
{

    /** @type Database */
    protected $db;

    public function __construct ()
    {
        $this->db = new Database();
    }

}