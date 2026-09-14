<?php

namespace Tests\Feature;

use App\Livewire\Contents\DocumentTrackerManagement;
use App\Mail\DocumentTrackerOfficeForwardedNotification;
use App\Mail\DocumentTrackerTransmittedNotification;
use App\Models\DocumentTracker;
use App\Models\RequestingOffice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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

    public function test_forwarding_emails_active_users_attached_to_the_target_office(): void
    {
        Mail::fake();

        $fromOffice = RequestingOffice::create(['name' => 'Records Section', 'status' => 'active']);
        $toOffice = RequestingOffice::create(['name' => 'SGOD - SMM and E Section', 'status' => 'active']);
        $otherOffice = RequestingOffice::create(['name' => 'Accounting Section', 'status' => 'active']);

        $actor = User::factory()->create([
            'email' => 'records@example.com',
            'position' => 'admin',
            'status' => 'active',
            'requesting_office_id' => $fromOffice->requesting_office_id,
        ]);

        $targetUser = User::factory()->create([
            'email' => 'smme@example.com',
            'position' => 'staff',
            'status' => 'active',
            'requesting_office_id' => $toOffice->requesting_office_id,
        ]);

        $inactiveTargetUser = User::factory()->create([
            'email' => 'inactive@example.com',
            'position' => 'staff',
            'status' => 'inactive',
            'requesting_office_id' => $toOffice->requesting_office_id,
        ]);

        $otherOfficeUser = User::factory()->create([
            'email' => 'accounting@example.com',
            'position' => 'staff',
            'status' => 'active',
            'requesting_office_id' => $otherOffice->requesting_office_id,
        ]);

        $tracker = DocumentTracker::create([
            'tracking_number' => '26-123456',
            'requestor_name' => 'Jane Doe',
            'requestor_email' => 'jane@example.com',
            'current_office_id' => $fromOffice->requesting_office_id,
            'document_type' => 'Memo',
            'details' => 'Quarterly report',
            'status' => 'pending',
            'received_by_user_id' => $actor->id,
            'received_at' => now(),
        ]);

        $this->actingAs($actor);

        Livewire::test(DocumentTrackerManagement::class)
            ->call('getDocumentTracker', $tracker->id)
            ->set('transfer_action', 'transmit')
            ->set('target_office_id', $toOffice->requesting_office_id)
            ->set('transfer_notes', 'For your review')
            ->call('submit_transfer_document_tracker');

        $this->assertSame($toOffice->requesting_office_id, $tracker->fresh()->current_office_id);

        // The requestor still gets the existing movement email.
        Mail::assertSent(DocumentTrackerTransmittedNotification::class, function ($mail) {
            return $mail->hasTo('jane@example.com');
        });

        // Only the active user attached to the target office is notified.
        Mail::assertSent(DocumentTrackerOfficeForwardedNotification::class, function ($mail) use ($targetUser, $toOffice, $fromOffice) {
            return $mail->hasTo($targetUser->email)
                && $mail->recipient->is($targetUser)
                && $mail->toOffice->is($toOffice)
                && $mail->fromOffice->is($fromOffice)
                && $mail->notes === 'For your review';
        });

        Mail::assertNotSent(DocumentTrackerOfficeForwardedNotification::class, function ($mail) use ($inactiveTargetUser) {
            return $mail->hasTo($inactiveTargetUser->email);
        });

        Mail::assertNotSent(DocumentTrackerOfficeForwardedNotification::class, function ($mail) use ($otherOfficeUser) {
            return $mail->hasTo($otherOfficeUser->email);
        });

        Mail::assertSent(DocumentTrackerOfficeForwardedNotification::class, 1);
    }

    public function test_forwarding_to_an_office_with_no_users_sends_no_office_email(): void
    {
        Mail::fake();

        $fromOffice = RequestingOffice::create(['name' => 'Records Section', 'status' => 'active']);
        $toOffice = RequestingOffice::create(['name' => 'Vacant Section', 'status' => 'active']);

        $actor = User::factory()->create([
            'email' => 'records@example.com',
            'position' => 'admin',
            'status' => 'active',
            'requesting_office_id' => $fromOffice->requesting_office_id,
        ]);

        $tracker = DocumentTracker::create([
            'tracking_number' => '26-654321',
            'requestor_name' => 'Jane Doe',
            'requestor_email' => 'jane@example.com',
            'current_office_id' => $fromOffice->requesting_office_id,
            'document_type' => 'Memo',
            'details' => 'Quarterly report',
            'status' => 'pending',
            'received_by_user_id' => $actor->id,
            'received_at' => now(),
        ]);

        $this->actingAs($actor);

        Livewire::test(DocumentTrackerManagement::class)
            ->call('getDocumentTracker', $tracker->id)
            ->set('transfer_action', 'transmit')
            ->set('target_office_id', $toOffice->requesting_office_id)
            ->call('submit_transfer_document_tracker');

        $this->assertSame($toOffice->requesting_office_id, $tracker->fresh()->current_office_id);

        Mail::assertSent(DocumentTrackerTransmittedNotification::class, 1);
        Mail::assertNotSent(DocumentTrackerOfficeForwardedNotification::class);
    }
}
