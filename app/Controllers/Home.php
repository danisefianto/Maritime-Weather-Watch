<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data ['title'] = "Dashboard";
        return view('dashboard', $data);
    }
    public function tools()
    {
        return view('tools');
    }
}
