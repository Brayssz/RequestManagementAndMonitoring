<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Requestor;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'position' => 'admin',
            'password' => Hash::make('password'), // Ensure password is hashed
            'status' => 'active',
        ]);

        // Requestor::create([
        //     'name' => 'John Doe',
        //     'email' => 'johndoe@example.com',
        //     'position' => 'principal',
        //     'status' => 'active',
        // ]);
    }
}
