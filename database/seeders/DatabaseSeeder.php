<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'AdminGeoGap@gmail.com',
            'password' => '1qaz!QAZ',
            'city' => 'Tbilisi',
            'phone' => '+995 593 122 122',
            'description' => '',
            'is_admin' => true,
        ]);
    }
}
