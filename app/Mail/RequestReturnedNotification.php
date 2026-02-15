<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestReturnedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $requestData;
    public $requestorName;
    public $schoolName;
    public $returnedToOfficeName;

    /**
     * Create a new message instance.
     */
    public function __construct(array $requestData, string $requestorName, string $schoolName, string $returnedToOfficeName)
    {
        $this->requestData = $requestData;
        $this->requestorName = $requestorName;
        $this->schoolName = $schoolName;
        $this->returnedToOfficeName = $returnedToOfficeName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Request Returned - DTS Tracker #' . $this->requestData['dts_tracker_number'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.request-returned',
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
