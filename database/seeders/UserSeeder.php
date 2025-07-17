<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@ritzaccounting.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Smith',
                'email' => 'john.smith@ritzaccounting.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@ritzaccounting.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@ritzaccounting.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'emma.wilson@ritzaccounting.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}