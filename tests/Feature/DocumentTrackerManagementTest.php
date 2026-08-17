<?php

namespace Tests\Feature;

use App\Livewire\Contents\DocumentTrackerManagement;
use App\Models\DocumentTracker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DocumentTrackerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_a_tracking_number_when_creating_a_document_tracker(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'position' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        Livewire::test(DocumentTrackerManagement::class)
            ->set('submit_func', 'add-document-tracker')
            ->set('requestor_name', 'Jane Doe')
            ->set('document_type', 'Memo')
            ->set('details', 'Quarterly report')
            ->set('tracking_number', '')
            ->call('submit_document_tracker');

        $tracker = DocumentTracker::first();

        $this->assertNotNull($tracker);
        $this->assertMatchesRegularExpression('/^\d{2}-\d{6}$/', $tracker->tracking_number);
        $this->assertSame('pending', $tracker->status);
    }
}
