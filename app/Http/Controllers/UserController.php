<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\UserStandard;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = User::orderBy('created_at', 'desc')->get();
        return view('pages.users',['users'=>$user]);
    }

    public function show($id)
    {
        $user = User::where('id',$id)->first();
        if (!$user){
            return redirect()->back()->with(['message','მომხმარებელი ვერ მოიძებნა']);
        }
        $product = Product::where('user_id',$user->id)->count();
        $application = UserStandard::where('user_id',$user->id)->distinct('group_id')->count();
        return view('pages.userDetail',
            [
                'user'=>$user,
                'product'=>$product,
                'application'=>$application
            ]
        );
    }
}
