<?php


namespace App\Controllers\Api;


use App\Classes\Controller;

class RPCController extends Controller
{
    function index ($json) {
        $data = [$json];
        $this->json($data);
    }
}