<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\UserStandard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'user'=>User::count(),
            'product'=>Product::count(),
            'application' =>UserStandard::count()
        ];
        return view('pages.dashboard',$data);
    }
}
