<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface UserServiceInterface
{
    /**
     * Register a new user
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array;

    /**
     * Login a user
     *
     * @param array $credentials
     * @return array|null
     */
    public function login(array $credentials): ?array;

    /**
     * Update user password
     *
     * @param User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return array
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): array;

    /**
     * Update user profile
     *
     * @param int $userId
     * @param array $data
     * @return User
     */
    public function updateProfile(int $userId, array $data): User;
} 