<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Retourne la vue "home.blade.php"
        return view('home');
    }
}
