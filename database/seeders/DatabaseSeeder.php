<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Requestor;
use App\Models\RequestingOffice;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'position' => 'admin',
                'password' => Hash::make('password'), // Ensure password is hashed
                'status' => 'active',
            ]
        );

        // Create a default requestor and five offices
        $requestor = Requestor::firstOrCreate(
            ['email' => 'requestor@example.com'],
            [
                'name' => 'Default Requestor',
                'position' => 'principal',
                'status' => 'active',
            ]
        );

        $offices = [
            'Finance Office',
            'Human Resources',
            'Planning and Development',
            'Procurement Office',
            'General Services',
        ];

        foreach ($offices as $officeName) {
            RequestingOffice::firstOrCreate([
                'name' => $officeName,
            ], [
                'type' => 'office',
                'requestor' => $requestor->requestor_id,
                'status' => 'active',
            ]);
        }
    }
}
