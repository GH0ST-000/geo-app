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
            'profile_picture' => 'sometimes|file|mimes:jpeg,png,jpg,gif,webp,bmp,svg|max:30000', // 30MB max size
        ], [
            'profile_picture.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg, gif, webp, bmp, or svg.',
            'profile_picture.max' => 'The profile picture must not be larger than 30MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle profile picture upload with Media Library
        if ($request->hasFile('profile_picture')) {
            try {
                // Get the uploaded file
                $file = $request->file('profile_picture');
                
                // Log file details for debugging
                \Log::info('Uploading profile picture', [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'size_mb' => round($file->getSize() / 1024 / 1024, 2) . 'MB',
                    'error' => $file->getError(),
                    'error_message' => $file->getErrorMessage(),
                    'php_ini_settings' => [
                        'upload_max_filesize' => ini_get('upload_max_filesize'),
                        'post_max_size' => ini_get('post_max_size'),
                        'memory_limit' => ini_get('memory_limit'),
                        'max_execution_time' => ini_get('max_execution_time'),
                    ],
                ]);
                
                // Check if file is valid
                if (!$file->isValid()) {
                    \Log::error('Invalid file upload', [
                        'error' => $file->getError(),
                        'error_message' => $file->getErrorMessage()
                    ]);
                    return response()->json([
                        'errors' => [
                            'profile_picture' => ['The profile picture upload failed: ' . $file->getErrorMessage()]
                        ]
                    ], 422);
                }
                
                // Process the upload synchronously
                $user->addMediaFromRequest('profile_picture')
                    ->sanitizingFileName(function($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->withResponsiveImages() // Add responsive image conversion
                    ->toMediaCollection('profile_picture');

                // Get media after upload
                $media = $user->fresh()->getFirstMedia('profile_picture');
                if (!$media) {
                    \Log::warning('Media library did not attach the file, trying direct file handling');
                    
                    // Fallback to direct file handling
                    try {
                        $path = $file->store('profile_pictures', 'public');
                        $user->profile_picture = 'storage/' . $path;
                        $user->save();
                        
                        \Log::info('Used direct file storage as fallback', [
                            'path' => $path,
                            'url' => $user->profile_picture
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Direct file storage also failed', [
                            'error' => $e->getMessage()
                        ]);
                        throw $e;
                    }
                }
                
                \Log::info('Profile picture uploaded successfully', [
                    'media_id' => $media->id,
                    'url' => $media->getUrl()
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to upload profile picture', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'errors' => [
                        'profile_picture' => ['The profile picture failed to upload: ' . $e->getMessage()]
                    ]
                ], 422);
            }
        }

        // Update user profile
        $updatedUser = $this->userService->updateProfile($user, $request->only([
            'first_name', 'last_name', 'city', 'phone', 'profile_picture'
        ]));

        // Add media URLs to the response
        $responseUser = $updatedUser->toArray();
        
        // Set the primary profile_picture_url as thumbnail for better performance
        $responseUser['profile_picture_url'] = $updatedUser->profile_thumbnail_url ?? $updatedUser->profile_picture_url;
        
        // Include all available image sizes
        $responseUser['profile_images'] = [
            'thumbnail' => $updatedUser->profile_thumbnail_url,
            'medium' => $updatedUser->profile_medium_url,
            'large' => $updatedUser->profile_large_url,
            'original' => $updatedUser->profile_picture_url
        ];

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $responseUser
        ]);
    }
}
