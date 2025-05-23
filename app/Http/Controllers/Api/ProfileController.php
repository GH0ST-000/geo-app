<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth:api');
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'age' => 'sometimes|integer|min:1|max:120',
            'personal_number' => 'sometimes|string|max:20',
            'gender' => 'sometimes|string|in:male,female,other',
            'profile_picture' => 'sometimes|nullable|file|mimes:jpeg,png,jpg,gif|max:30000',
            'description' => 'sometimes|nullable|string|max:1000',
        ], [
            'profile_picture.file' => 'The profile picture must be a file.',
            'profile_picture.mimes' => 'The profile picture must be a jpeg, png, jpg, or gif file.',
            'profile_picture.max' => 'The profile picture must be less than 30MB.',
            'description.max' => 'The description cannot be longer than 1000 characters.',
        ]);

        if ($validator->fails()) {
            \Log::warning('Profile update validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->except(['profile_picture']),
                'has_file' => $request->hasFile('profile_picture'),
                'file_info' => $request->hasFile('profile_picture') ? [
                    'name' => $request->file('profile_picture')->getClientOriginalName(),
                    'mime' => $request->file('profile_picture')->getMimeType(),
                    'size' => $request->file('profile_picture')->getSize(),
                ] : null
            ]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle profile picture upload with Media Library
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            try {
                // Log upload attempt
                \Log::info('Attempting to upload profile picture', [
                    'original_name' => $request->file('profile_picture')->getClientOriginalName(),
                    'mime_type' => $request->file('profile_picture')->getMimeType(),
                    'size' => $request->file('profile_picture')->getSize(),
                    'size_mb' => round($request->file('profile_picture')->getSize() / 1048576, 2) . 'MB',
                    'error' => $request->file('profile_picture')->getError(),
                    'error_message' => $request->file('profile_picture')->getErrorMessage(),
                    'php_ini_settings' => [
                        'upload_max_filesize' => ini_get('upload_max_filesize'),
                        'post_max_size' => ini_get('post_max_size'),
                        'memory_limit' => ini_get('memory_limit'),
                        'max_execution_time' => ini_get('max_execution_time'),
                    ]
                ]);

                // Check if file is valid
                if (!$request->file('profile_picture')->isValid()) {
                    return response()->json([
                        'errors' => [
                            'profile_picture' => [
                                'The profile picture upload failed: ' .
                                $request->file('profile_picture')->getErrorMessage()
                            ]
                        ]
                    ], 422);
                }

                // Add the file to the media collection
                try {
                    $media = $user->addMediaFromRequest('profile_picture')
                        ->toMediaCollection('profile_picture');

                    // Log success
                    \Log::info('Profile picture uploaded successfully', [
                        'media_id' => $media->id,
                        'url' => $media->getUrl()
                    ]);
                } catch (\Exception $innerException) {
                    \Log::error('Media collection upload failed', [
                        'message' => $innerException->getMessage(),
                        'trace' => $innerException->getTraceAsString()
                    ]);

                    return response()->json([
                        'errors' => [
                            'profile_picture' => [
                                'The profile picture failed to upload.'
                            ]
                        ]
                    ], 422);
                }

                // Update the profile_picture field for backward compatibility
                try {
                    $mediaUrl = $user->getFirstMedia('profile_picture')->getUrl();
                    $request->merge(['profile_picture' => $mediaUrl]);
                } catch (\Exception $mediaException) {
                    \Log::error('Failed to get media URL', [
                        'message' => $mediaException->getMessage()
                    ]);

                    return response()->json([
                        'errors' => [
                            'profile_picture' => [
                                'The profile picture failed to upload.'
                            ]
                        ]
                    ], 422);
                }
            } catch (\Exception $e) {
                \Log::error('Profile picture upload exception', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'errors' => [
                        'profile_picture' => [
                            'The profile picture failed to upload.'
                        ]
                    ]
                ], 422);
            }
        }

        // Update user profile
        $updatedUser = $this->userService->updateProfile($user->id, $request->only([
            'first_name', 'last_name', 'city', 'phone', 'email', 'age', 'personal_number', 'gender', 'profile_picture', 'description'
        ]));

        // Add media URLs to the response
        $responseUser = $updatedUser->toArray();

        // Always use the original image URL to avoid 404 errors when conversions are still processing
        $mediaUrl = null;
        $media = $updatedUser->getFirstMedia('profile_picture');

        if ($media) {
            // Always use the original image URL initially
            // Conversions will be processed in the background and will be available later
            $mediaUrl = $media->getUrl();

            // Log the URL being returned
            \Log::info('Profile picture URL being returned', [
                'media_id' => $media->id,
                'url' => $mediaUrl,
                'has_medium_conversion' => $media->hasGeneratedConversion('medium')
            ]);
        }

        $responseUser['profile_picture_url'] = $mediaUrl;

        // Remove the media collection from the response to keep it clean
        unset($responseUser['media']);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $responseUser
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'profile_picture' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'age' => 'nullable|integer|min:1|max:120',
            'personal_number' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:male,female,other',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = $this->userService->updateProfile(auth()->id(), $validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
