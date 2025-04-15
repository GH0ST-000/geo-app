<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        $user = $this->userRepository->create($data);
        $token = Auth::login($user);
        $userResponse = $user->toArray();
        
        return [
            'user' => $userResponse,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ];
    }

    /**
     * Login a user
     *
     * @param array $credentials
     * @return array|null
     */
    public function login(array $credentials): ?array
    {
        if (!$token = Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();
        $userData = $user->toArray();
        
        // Add profile picture URLs
        $userData['profile_picture_url'] = $user->profile_picture_url;
        $userData['profile_thumbnail_url'] = $user->profile_thumbnail_url;
        $userData['profile_medium_url'] = $user->profile_medium_url;
        
        return [
            'user' => $userData,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ];
    }

    /**
     * Update user password
     *
     * @param User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return array
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): array
    {
        // Check if current password matches
        if (!Hash::check($currentPassword, $user->password)) {
            return [
                'success' => false,
                'message' => 'Current password is incorrect'
            ];
        }

        // Check if new password is same as old password
        if (Hash::check($newPassword, $user->password)) {
            return [
                'success' => false,
                'message' => 'New password must be different from current password'
            ];
        }

        // Update password
        $this->userRepository->updatePassword($user, $newPassword);

        return [
            'success' => true,
            'message' => 'Password updated successfully'
        ];
    }
    
    /**
     * Update user profile
     *
     * @param int $userId
     * @param array $data
     * @return User
     */
    public function updateProfile(int $userId, array $data): User
    {
        return $this->userRepository->updateProfile($userId, $data);
    }

    private function handleProfilePictureUpload($file): string
    {
        $path = $file->store('profile-pictures', 'public');
        return Storage::url($path);
    }
} 