<?php


namespace App\Classes;


abstract class Model
{

    /** @type Database */
    protected $db;

    public function __construct ()
    {
        $this->db = new Database();
    }

}