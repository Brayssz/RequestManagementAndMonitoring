<?php

namespace App\Mail;

use App\Models\DocumentTracker;
use App\Models\RequestingOffice;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentTrackerOfficeForwardedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public DocumentTracker $documentTracker;

    public User $recipient;

    public RequestingOffice $toOffice;

    public ?RequestingOffice $fromOffice;

    public ?User $forwardedBy;

    public ?string $notes;

    /**
     * Create a new message instance.
     *
     * Sent to each user attached to the office a document was forwarded (or
     * returned) to, so the receiving office knows a document is on its way.
     */
    public function __construct(
        DocumentTracker $documentTracker,
        User $recipient,
        RequestingOffice $toOffice,
        ?RequestingOffice $fromOffice = null,
        ?User $forwardedBy = null,
        ?string $notes = null
    ) {
        $this->documentTracker = $documentTracker;
        $this->recipient = $recipient;
        $this->toOffice = $toOffice;
        $this->fromOffice = $fromOffice;
        $this->forwardedBy = $forwardedBy;
        $this->notes = $notes;
    }

    /**
     * Build the message.
     *
     * Uses build() rather than content() so the template can embed the footer
     * logos with $message->embed(), matching the other document tracker mails.
     */
    public function build()
    {
        $subjectPrefix = $this->documentTracker->status === 'returned'
            ? 'Document Returned to Your Office: '
            : 'Document Forwarded to Your Office: ';

        return $this->subject($subjectPrefix . $this->documentTracker->tracking_number)
                    ->view('emails.document-tracker-office-forwarded')
                    ->with([
                        'documentTracker' => $this->documentTracker,
                        'recipient' => $this->recipient,
                        'toOffice' => $this->toOffice,
                        'fromOffice' => $this->fromOffice,
                        'forwardedBy' => $this->forwardedBy,
                        'notes' => $this->notes,
                    ]);
    }
}
