<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

    public function edit($id)
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
        return view('pages.editProduct',['product'=>$product,'user'=>$fullName,'images'=>$products]);
    }


    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'packing_capacity' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'product_images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:20480', // 20MB max
        ]);

        // Find the product
        $product = Product::findOrFail($id);

        // Update product fields
        $product->product_name = $request->product_name;
        $product->product_description = $request->product_description;
        $product->packing_capacity = $request->packing_capacity;
        $product->address = $request->address;
        $product->save();

        // Handle product images if any were uploaded
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                if ($image->isValid()) {
                    $product->addMedia($image)
                        ->toMediaCollection('product_images');
                }
            }
        }

        // Handle deleted images
        if ($request->has('delete_images') && is_array($request->delete_images)) {
            foreach ($request->delete_images as $mediaId) {
                $media = $product->media()->find($mediaId);
                if ($media) {
                    $media->delete();
                }
            }
        }

        return redirect(url('admin/products'))->with('message', 'პროდუქტი წარმატებით განახლდა');

    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete all associated media
        $product->clearMediaCollection('product_images');

        // Delete the product
        $product->delete();

        return redirect()->route('products')
            ->with('success', 'პროდუქტი წარმატებით წაიშალა');
    }

    public function deleteImage($id)
    {
        // Find the media item
        $media = Media::findOrFail($id);

        // Get the product ID before deletion for redirect
        $productId = $media->model_id;

        $media->delete();

        return redirect()->route('products-edit', $productId)->with('message', 'სურათი წარმატებით წაიშალა');
    }
}
