<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // Create Employee Users
        $employees = [
            [
                'name' => 'John Employee',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'role' => 'employee'
            ],
            [
                'name' => 'Sarah Employee',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password123'),
                'role' => 'employee'
            ],
            [
                'name' => 'Mike Employee',
                'email' => 'mike@example.com',
                'password' => Hash::make('password123'),
                'role' => 'employee'
            ]
        ];

        foreach ($employees as $employee) {
            User::create($employee);
        }
    }
}
