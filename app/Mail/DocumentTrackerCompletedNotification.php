<?php

namespace App\Mail;

use App\Models\DocumentTracker;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentTrackerCompletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public DocumentTracker $documentTracker;

    /**
     * Create a new message instance.
     */
    public function __construct(DocumentTracker $documentTracker)
    {
        $this->documentTracker = $documentTracker;
    }

    /**
     * Build the message.
     *
     * Shares the movement notification template with the forwarded/returned
     * mail so the three emails keep an identical layout; the template picks its
     * wording and accent colour from the tracker status.
     */
    public function build()
    {
        return $this->subject('Document Completed: ' . $this->documentTracker->tracking_number)
                    ->view('emails.document-tracker-transmitted')
                    ->with(['documentTracker' => $this->documentTracker]);
    }
}
