<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestDeletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $requestData;
    public $requestorName;
    public $schoolName;

    /**
     * Create a new message instance.
     */
    public function __construct(array $requestData, string $requestorName, string $schoolName)
    {
        $this->requestData = $requestData;
        $this->requestorName = $requestorName;
        $this->schoolName = $schoolName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Request Deleted - DTS Tracker #' . $this->requestData['dts_tracker_number'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.request-deleted',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
