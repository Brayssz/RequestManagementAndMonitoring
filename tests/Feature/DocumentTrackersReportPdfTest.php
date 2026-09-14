<?php

namespace Tests\Feature;

use App\Models\DocumentTracker;
use App\Models\RequestingOffice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class DocumentTrackersReportPdfTest extends TestCase
{
    use RefreshDatabase;

    protected function makeTracker(string $trackingNumber, string $status, ?int $officeId = null): DocumentTracker
    {
        return DocumentTracker::create([
            'tracking_number' => $trackingNumber,
            'requestor_name' => 'Jane Doe',
            'current_office_id' => $officeId,
            'document_type' => 'Memo',
            'details' => 'Quarterly report',
            'status' => $status,
        ]);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/document-trackers-report-pdf?status=pending')
            ->assertRedirect('/login');
    }

    public function test_it_streams_a_pdf_for_the_requested_status(): void
    {
        $user = User::factory()->create(['position' => 'admin', 'status' => 'active']);

        $this->actingAs($user)
            ->get('/document-trackers-report-pdf?status=pending')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_the_report_only_lists_trackers_matching_the_card_status(): void
    {
        $office = RequestingOffice::create(['name' => 'Records Section', 'status' => 'active']);

        $this->makeTracker('26-000001', 'pending', $office->requesting_office_id);
        $this->makeTracker('26-000002', 'transmitted', $office->requesting_office_id);
        $this->makeTracker('26-000003', 'completed', $office->requesting_office_id);

        $user = User::factory()->create(['position' => 'admin', 'status' => 'active']);

        $rendered = null;
        View::composer('pdf.document-trackers-report-pdf', function ($view) use (&$rendered) {
            $rendered = $view->getData();
        });

        $this->actingAs($user)
            ->get('/document-trackers-report-pdf?status=transmitted')
            ->assertOk();

        $this->assertNotNull($rendered);
        $this->assertSame('transmitted', $rendered['status']);
        $this->assertSame('Forwarded Document Trackers', $rendered['reportSubtitle']);
        $this->assertSame(['26-000002'], $rendered['documentTrackers']->pluck('tracking_number')->all());
    }

    public function test_an_unknown_status_falls_back_to_every_tracker(): void
    {
        $this->makeTracker('26-000001', 'pending');
        $this->makeTracker('26-000002', 'completed');

        $user = User::factory()->create(['position' => 'admin', 'status' => 'active']);

        $rendered = null;
        View::composer('pdf.document-trackers-report-pdf', function ($view) use (&$rendered) {
            $rendered = $view->getData();
        });

        $this->actingAs($user)
            ->get('/document-trackers-report-pdf?status=not-a-status')
            ->assertOk();

        $this->assertNotNull($rendered);
        $this->assertSame('all', $rendered['status']);
        $this->assertSame('All Document Trackers', $rendered['reportSubtitle']);
        $this->assertCount(2, $rendered['documentTrackers']);
    }
}
