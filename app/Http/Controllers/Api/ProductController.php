<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Auth::user()->products()
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $products->each(function ($product) {
            $product->product_images = $product->getProductImagesAttribute();
        });
        
        return response()->json($products, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'packing_capacity' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'product_images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:10240',
            'product_file.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = new Product();
        $product->user_id = Auth::id();
        $product->product_name = $request->product_name;
        $product->product_description = $request->product_description;
        $product->packing_capacity = $request->packing_capacity;
        $product->address = $request->address;
        $product->save();

        // Handle product images from product_images field if any
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                if ($image->isValid()) {
                    $product->addMedia($image)
                        ->toMediaCollection('product_images');
                }
            }
        }
        
        // Handle product images from product_file field if any
        if ($request->hasFile('product_file')) {
            foreach ($request->file('product_file') as $image) {
                if ($image->isValid()) {
                    $product->addMedia($image)
                        ->toMediaCollection('product_images');
                }
            }
        }
        
        // Load images URLs
        $product->product_images = $product->getProductImagesAttribute();

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('media')->findOrFail($id);
        
        // Check if the current user owns this product
        if ($product->user_id != Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to view this product'
            ], 403);
        }
        
        // Load images URLs
        $product->product_images = $product->getProductImagesAttribute();
        
        return response()->json($product, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Request $request Request data
     * @param string $id Product ID
     * @return \Illuminate\Http\JsonResponse
     * 
     * Using POST method to support form data with file uploads
     * Image handling:
     * - Files sent as 'product_images[]' or 'product_file[]' will ALWAYS be ADDED (not replaced)
     * - Same keys are used in both create and update methods
     * - To delete images: Send an array of media IDs in 'delete_images[]'
     * - To delete a single image: Use the dedicated endpoint DELETE /products/{productId}/images/{imageId}
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        
        // Check if the current user owns this product
        if ($product->user_id != Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to update this product'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'product_name' => 'sometimes|required|string|max:255',
            'product_description' => 'sometimes|required|string',
            'packing_capacity' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'product_images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:10240',
            'product_file.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:10240',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Update product fields
        if ($request->has('product_name')) {
            $product->product_name = $request->product_name;
        }
        
        if ($request->has('product_description')) {
            $product->product_description = $request->product_description;
        }
        
        if ($request->has('packing_capacity')) {
            $product->packing_capacity = $request->packing_capacity;
        }
        
        if ($request->has('address')) {
            $product->address = $request->address;
        }
        
        if ($request->has('is_active')) {
            $product->is_active = $request->is_active;
        }
        
        $product->save();
        
        // Handle product images if any
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                if ($image->isValid()) {
                    $product->addMedia($image)
                        ->toMediaCollection('product_images');
                }
            }
        }
        
        // Handle product images from product_file field if any
        if ($request->hasFile('product_file')) {
            foreach ($request->file('product_file') as $image) {
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
        
        // Load fresh media
        $product->load('media');
        
        // Load images URLs
        $product->product_images = $product->getProductImagesAttribute();
        
        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        
        // Check if the current user owns this product
        if ($product->user_id != Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to delete this product'
            ], 403);
        }
        
        // Delete all associated media
        $product->clearMediaCollection('product_images');
        
        // Delete the product
        $product->delete();
        
        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }
    
    /**
     * Get all products (public endpoint)
     */
    public function getAllProducts(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $query = Product::with('media')
            ->where('is_active', true);
            
        // Filter by search term if provided
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('product_name', 'like', "%{$searchTerm}%")
                  ->orWhere('product_description', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%");
            });
        }
        
        $products = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);
            
        // Transform products to include image URLs
        $products->getCollection()->transform(function ($product) {
            $product->product_images = $product->getProductImagesAttribute();
            $product->user = [
                'id' => $product->user->id,
                'first_name' => $product->user->first_name,
                'last_name' => $product->user->last_name,
                'profile_picture_url' => $product->user->profile_picture_url,
            ];
            return $product;
        });
        
        return response()->json($products, 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Get a specific product (public endpoint)
     */
    public function getProduct(string $id)
    {
        $product = Product::with(['media', 'user'])
            ->where('is_active', true)
            ->findOrFail($id);
            
        // Load images URLs
        $product->product_images = $product->getProductImagesAttribute();
        
        // Transform user data
        $product->user = [
            'id' => $product->user->id,
            'first_name' => $product->user->first_name,
            'last_name' => $product->user->last_name,
            'profile_picture_url' => $product->user->profile_picture_url,
        ];
        
        return response()->json($product, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Delete a single product image
     */
    public function deleteImage(Request $request, string $productId, string $imageId)
    {
        $product = Product::findOrFail($productId);
        
        // Check if the current user owns this product
        if ($product->user_id != Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to modify this product'
            ], 403);
        }
        
        // Find and delete the image
        $media = $product->media()->find($imageId);
        
        if (!$media) {
            return response()->json([
                'message' => 'Image not found'
            ], 404);
        }
        
        $media->delete();
        
        // Reload the product with fresh images
        $product->load('media');
        $product->product_images = $product->getProductImagesAttribute();
        
        return response()->json([
            'message' => 'Image deleted successfully',
            'product' => $product
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get products for a specific user (public endpoint)
     * 
     * @param string $userId User ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProducts(string $userId, Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $query = Product::with(['media', 'user'])
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc');
        
        $products = $query->paginate($perPage);
        
        // Transform products to include image URLs
        $transformedProducts = $products->getCollection()->map(function ($product) {
            $productArray = $product->toArray();
            $productArray['product_images'] = $product->getProductImagesAttribute();
            
            // Include basic user info
            if ($product->user) {
                $productArray['user'] = [
                    'id' => $product->user->id,
                    'first_name' => $product->user->first_name,
                    'last_name' => $product->user->last_name,
                    'profile_picture_url' => $product->user->profile_picture_url,
                ];
            }
            
            // Remove the media collection to keep response clean
            unset($productArray['media']);
            
            return $productArray;
        });
        
        // Create a new paginator with transformed products
        $paginatedProducts = new \Illuminate\Pagination\LengthAwarePaginator(
            $transformedProducts,
            $products->total(),
            $products->perPage(),
            $products->currentPage(),
            [
                'path' => \Illuminate\Support\Facades\Request::url(),
                'query' => $request->query(),
            ]
        );
        
        return response()->json($paginatedProducts, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
