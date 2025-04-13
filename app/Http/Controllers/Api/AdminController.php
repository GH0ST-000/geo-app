<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth:api');
        $this->middleware('admin'); // You'll need to create this middleware
    }

    /**
     * Update user verification status
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserVerification(Request $request, int $userId): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'is_verified' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the user
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Update verification status
        $updatedUser = $this->userService->updateProfile($user, [
            'is_verified' => $request->is_verified
        ]);

        return response()->json([
            'message' => 'User verification status updated successfully',
            'user' => [
                'id' => $updatedUser->id,
                'email' => $updatedUser->email,
                'first_name' => $updatedUser->first_name,
                'last_name' => $updatedUser->last_name,
                'is_verified' => $updatedUser->is_verified,
            ]
        ]);
    }
} 