<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
} 