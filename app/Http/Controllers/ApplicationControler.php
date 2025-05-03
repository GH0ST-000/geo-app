<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicationControler extends Controller
{
    public function index()
    {
        return view('pages.applications');
    }
}
