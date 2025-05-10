<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id','desc')->get();
        return view('pages.products',['products'=>$products]);
    }

    public function show($id)
    {
        $product = Product::where('id',$id)->first();
        $users =User::where('id',$product->user_id)->first();
        $fullName = $users->first_name . ' ' .  $users->last_name;
        $products = Product::where('id',$id)
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->get();

        $products->each(function ($product) {
            $product->product_images = $product->getProductImagesAttribute();
        });
        return view('pages.productDetail',['product'=>$product,'user'=>$fullName,'images'=>$products]);
    }
}
