<?php


namespace App\Controllers;


use App\Classes\Controller;

class AuthController extends Controller
{
    function __construct()
    {
    }

    public function index()
    {
        //
        $data = [];
        $this->view('login', $data);
    }
    public function login()
    {
        $data = [];
        //
        $this->json($data);
    }
    public function register()
    {
        $data = [];
        //
        $this->json($data);
    }

    public function logout()
    {
        //
        $this->response->redirect('/login');
    }

}