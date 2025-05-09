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



        $standard = [
            'honey_standard' => 'თაფლის მოდული',
            'dairy_standard' => 'რძის მოდული',
            'crop_standard' =>'მემცენარეობის მოდული',
        ];
        $applications = UserStandard::orderBy('created_at','desc')->take(3)->get();
        $data1 =[];
        if (!empty($applications)){
            foreach ($applications as $application){
                $user = User::where('id',$application->user_id)->first();

                $data1 [] =[
                    'id'=>$application->id,
                    'fullName'=>$user->first_name . ' '. $user->last_name,
                    'standard'=>$standard[$application->slug],
                    'created_at'=>$application->created_at->diffForHumans()
                ];
            }
        }
        return view('pages.dashboard', array_merge($data, ['applications' => $data1]));
    }
}
