<?php

namespace App\Controllers;


use App\Classes\Config;
use App\Classes\Controller;
use App\Classes\SPDO;
use App\Classes\Template;
use App\Classes\TemplateException;

class MainController extends Controller
{

    public function index()
    {
        $this->variable('title', 'Framework light');
        $this->view('main', [
            'header' => 'Index page',
        ]);
    }

    public function news()
    {
        $this->variable('title', 'Framework light');
        $this->view('main', [
            'header' => 'News page',
        ]);
    }

    public function blog()
    {
        $this->variable('title', 'Framework light');
        $this->view('main', [
            'header' => 'Blog page',
        ]);
    }

    public function contacts()
    {
        $this->variable('title', 'Framework light');
        $this->view('main', [
            'header' => 'Contacts page',
        ]);
    }
}