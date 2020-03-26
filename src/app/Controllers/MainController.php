<?php

namespace App\Controllers;


use App\Classes\Controller;
use App\Classes\Database;

class MainController extends Controller
{

    public function index()
    {
        // get db connection
        // $db = new Database();

        $this->view('main', [
            'header' => 'Index page',
        ]);
    }

    public function news()
    {
        $this->view('main', [
            'header' => 'News page',
        ]);
    }

    public function blog()
    {
        $this->view('main', [
            'header' => 'Blog page',
        ]);
    }

    public function contacts()
    {
        $this->variable('title', 'Contacts');

        $this->view('main', [
            'header' => 'Contacts page',
        ]);
    }
}