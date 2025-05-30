<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        
        // Initialize description as null if not provided
        if (!isset($data['description']) || $data['description'] === '') {
            $data['description'] = null;
        }
        
        // Set default verification status
        if (!isset($data['is_verified'])) {
            $data['is_verified'] = false;
        }
        
        return $this->model->create($data);
    }

    /**
     * Find user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Find user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Update user password
     *
     * @param User $user
     * @param string $password
     * @return User
     */
    public function updatePassword(User $user, string $password)
    {
        $user->password = Hash::make($password);
        $user->save();
        
        return $user;
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
        $user = User::findOrFail($userId);
        $user->update($data);
        return $user;
    }
} 