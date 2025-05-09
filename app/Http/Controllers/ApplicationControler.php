<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserStandard;
use Illuminate\Http\Request;

class ApplicationControler extends Controller
{
    public function index()
    {
         $standard = [
            'honey_standard' => 'თაფლის მოდული',
            'dairy_standard' => 'რძის მოდული',
            'crop_standard' =>'მემცენარეობის მოდული',
        ];
        $applications = UserStandard::all();
        $data =[];
        if (!empty($applications)){
            foreach ($applications as $application){
                $user = User::where('id',$application->user_id)->first();

                $data [] =[
                    'id'=>$application->id,
                    'fullName'=>$user->first_name . ' '. $user->last_name,
                    'standard'=>$standard[$application->slug],
                    'created_at'=>$application->created_at->diffForHumans()
                ];
             }
        }

        return view('pages.applications',['applications'=>$data]);
    }
}
