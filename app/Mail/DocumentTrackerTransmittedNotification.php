<?php

namespace App\Mail;

use App\Models\DocumentTracker;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentTrackerTransmittedNotification extends Mailable
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
     */
    public function build()
    {
        return $this->subject('Document Forwarded: ' . $this->documentTracker->tracking_number)
                    ->view('emails.document-tracker-transmitted')
                    ->with(['documentTracker' => $this->documentTracker]);
    }
}
