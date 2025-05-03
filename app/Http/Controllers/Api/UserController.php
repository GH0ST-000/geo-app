<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Get all verified users with pagination
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVerifiedUsers(Request $request)
    {
        $perPage = $request->get('per_page', 9); // Default is 9 per page as requested
        
        $query = User::where('is_verified', true);
        
        // Filter by gender if provided
        if ($request->has('gender') && in_array($request->gender, ['male', 'female', 'other'])) {
            $query->where('gender', $request->gender);
        }
        
        // Filter by search term
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('city', 'like', "%{$searchTerm}%");
            });
        }
        
        // Filter by city
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }
        
        // Filter by age range
        if ($request->has('min_age')) {
            $query->where('age', '>=', (int)$request->min_age);
        }
        
        if ($request->has('max_age')) {
            $query->where('age', '<=', (int)$request->max_age);
        }
        
        $users = $query->paginate($perPage);
            
        // Transform users to include profile picture URL and hide sensitive data
        $transformedUsers = $users->getCollection()->map(function ($user) {
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'city' => $user->city,
                'profile_picture_url' => $user->profile_picture_url,
                'profile_thumbnail_url' => $user->profile_thumbnail_url,
                'profile_medium_url' => $user->profile_medium_url,
                'gender' => $user->gender,
                'age' => $user->age,
                'description' => $user->description,
                'user_type' => $user->user_type,
            ];
        });
        
        // Create a new paginator with transformed users
        $paginatedUsers = new \Illuminate\Pagination\LengthAwarePaginator(
            $transformedUsers,
            $users->total(),
            $users->perPage(),
            $users->currentPage(),
            [
                'path' => \Illuminate\Support\Facades\Request::url(),
                'query' => $request->query(),
            ]
        );
        
        return response()->json($paginatedUsers, 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Deactivate and delete user account along with all associated data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivateAccount(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Validate the password if provided for security
        if ($request->has('password')) {
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'The provided password is incorrect.'
                ], 403);
            }
        }
        
        try {
            // Start a database transaction to ensure all related data is deleted
            \DB::beginTransaction();
            
            // 1. Delete all user's products and their media
            $products = Product::where('user_id', $user->id)->get();
            foreach ($products as $product) {
                // Delete product media
                $product->clearMediaCollection('product_images');
                
                // Delete the product
                $product->delete();
            }
            
            // 2. Delete user's profile picture if exists
            if ($user->getFirstMedia('profile_pictures')) {
                $user->clearMediaCollection('profile_pictures');
            }
            
            // 3. Delete any other data associated with the user (add more if needed)
            // Example: Delete user comments
            // $user->comments()->delete();
            
            // 4. Finally, delete the user
            $user->delete();
            
            \DB::commit();
            
            // Log out the user by invalidating the token
            Auth::logout();
            
            return response()->json([
                'message' => 'Your account and all associated data have been permanently deleted.'
            ]);
            
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            \DB::rollBack();
            
            return response()->json([
                'message' => 'Failed to deactivate account',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 