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
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateProfile(User $user, array $data)
    {
        // Remove profile_picture if it's a temporary path
        if (isset($data['profile_picture']) && 
            (strpos($data['profile_picture'], '/tmp/') !== false || 
             strpos($data['profile_picture'], '/var/folders/') !== false ||
             strpos($data['profile_picture'], 'php') !== false)) {
            unset($data['profile_picture']);
        }
        
        $user->fill($data);
        $user->save();
        
        return $user->fresh();
    }
} 