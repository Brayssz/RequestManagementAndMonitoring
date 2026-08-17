<?php

namespace Tests\Feature;

use App\Livewire\Contents\UserManagement;
use App\Models\RequestingOffice;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    public function test_user_can_be_created_with_an_assigned_office(): void
    {
        $office = RequestingOffice::create([
            'name' => 'Finance Office',
            'type' => 'office',
            'status' => 'active',
        ]);

        Livewire::test(UserManagement::class)
            ->set('submit_func', 'add-user')
            ->set('name', 'Jane Doe')
            ->set('email', 'jane@example.com')
            ->set('position', 'clerk')
            ->set('requesting_office_id', $office->requesting_office_id)
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('submit_user');

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'requesting_office_id' => $office->requesting_office_id,
        ]);
    }
}
