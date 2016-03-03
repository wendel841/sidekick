<?php

namespace App\Http\Controllers;


class HomeController extends Controller
{
    public function getDashboard()
    {
        return view('dashboard');
    }
}