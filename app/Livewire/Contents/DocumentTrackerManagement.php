<?php

namespace App\Livewire\Contents;

use App\Models\DocumentTracker;
use App\Models\RequestingOffice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\DocumentTrackerCreatedNotification;
use App\Mail\DocumentTrackerTransmittedNotification;
use Livewire\Component;

class DocumentTrackerManagement extends Component
{
    public $submit_func;

    public $documentTracker;

    public $document_tracker_id, $tracking_number, $requestor_name, $current_office_id, $document_type, $details, $status;
    public $requestor_email;
    public $requesting_office_id;
    public $requestingOffices;
    public $selected_requesting_office_id;

    public $transfer_action, $target_office_id, $transfer_notes;

    public $currentOffices;

    public function mount()
    {
        $this->currentOffices = RequestingOffice::where('status', 'active')->orderBy('name', 'asc')->get();
        $this->requestingOffices = RequestingOffice::where('status', 'active')->orderBy('name', 'asc')->get();

        if (Auth::check()) {
            $this->current_office_id = Auth::user()->requesting_office_id ?? null;
        }
    }

    public function updatedSelectedRequestingOfficeId($value)
    {
        // If external or empty, leave fields editable
        if (empty($value) || $value === 'external') {
            $this->requesting_office_id = null;
            $this->requestor_name = $this->requestor_name ?? null;
            $this->requestor_email = $this->requestor_email ?? null;
            return;
        }

        $office = RequestingOffice::find($value);

        $this->requesting_office_id = $office->requesting_office_id ?? null;

        if ($office && $office->requestor_obj) {
            $this->requestor_name = $office->requestor_obj->name;
            $this->requestor_email = $office->requestor_obj->email;
        } else {
            // If no linked requestor, default the requestor name to office name
            $this->requestor_name = $office->name ?? $this->requestor_name;
            $this->requestor_email = $this->requestor_email ?? null;
        }
    }

    public function getDocumentTracker($documentTrackerId)
    {
        $this->documentTracker = DocumentTracker::find($documentTrackerId);

        if ($this->documentTracker) {
            $this->document_tracker_id = $this->documentTracker->id;
            $this->tracking_number = $this->documentTracker->tracking_number;
            $this->requestor_name = $this->documentTracker->requestor_name;
            $this->requestor_email = $this->documentTracker->requestor_email;
            $this->requesting_office_id = $this->documentTracker->requesting_office_id;
            $this->selected_requesting_office_id = $this->documentTracker->requesting_office_id ?: 'external';
            $this->current_office_id = $this->documentTracker->current_office_id;
            $this->document_type = $this->documentTracker->document_type;
            $this->details = $this->documentTracker->details;
            $this->status = $this->documentTracker->status;

            $this->target_office_id = $this->documentTracker->current_office_id;
        } else {
            session()->flash('error', 'Document tracker not found.');
        }
    }

    protected function rules()
    {
        return [
            'tracking_number' => 'nullable|string|max:255',
            'requestor_name' => 'required|string|max:255',
            'requestor_email' => 'nullable|email|max:255',
            'requesting_office_id' => 'nullable|exists:requesting_offices,requesting_office_id',
            'current_office_id' => 'nullable|exists:requesting_offices,requesting_office_id',
            'document_type' => 'required|string|max:255',
            'details' => 'nullable|string',
            'status' => 'nullable|string|max:255',
        ];
    }

    protected function generateUniqueTrackingNumber(): string
    {
        do {
            $year = substr(date('Y'), -2);
            $randomNumber = random_int(100000, 999999);
            $trackingNumber = $year . '-' . $randomNumber;
        } while (DocumentTracker::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }

    protected function transferRules()
    {
        return [
            'target_office_id' => 'required|exists:requesting_offices,requesting_office_id',
            'transfer_notes' => 'nullable|string|max:1000',
        ];
    }

    public function render()
    {
        return view('livewire.contents.document-tracker-management');
    }

    public function resetFields()
    {
        $this->reset([
            'tracking_number', 'requestor_name', 'requestor_email', 'requesting_office_id', 'selected_requesting_office_id',
            'current_office_id', 'document_type', 'details', 'status'
        ]);

        if (Auth::check()) {
            $this->current_office_id = Auth::user()->requesting_office_id ?? null;
        }
    }

    public function resetTransferFields()
    {
        $this->reset([
            'transfer_action', 'target_office_id', 'transfer_notes'
        ]);
    }

    public function submit_document_tracker()
    {
        if (empty($this->current_office_id) && Auth::check()) {
            $this->current_office_id = Auth::user()->requesting_office_id ?? null;
        }

        $this->tracking_number = $this->tracking_number ?: $this->generateUniqueTrackingNumber();

        $this->validate();

        if ($this->submit_func == 'add-document-tracker') {
            $tracker = DocumentTracker::create([
                'tracking_number' => $this->tracking_number,
                'requestor_name' => $this->requestor_name,
                'requestor_email' => $this->requestor_email,
                'requesting_office_id' => $this->requesting_office_id ?: null,
                'current_office_id' => $this->current_office_id,
                'document_type' => $this->document_type,
                'details' => $this->details,
                'status' => 'pending',
                'received_by_user_id' => Auth::id(),
                'received_at' => now(),
            ]);

            // Send notification email to requestor if email provided
            if (!empty($this->requestor_email) && $tracker) {
                try {
                    Mail::to($this->requestor_email)->send(new DocumentTrackerCreatedNotification($tracker));
                } catch (\Exception $e) {
                    // Log error but do not block the flow
                    Log::error('Failed to send document tracker created email: ' . $e->getMessage());
                }
            }

            session()->flash('message', 'Document tracker successfully created.');
        } elseif ($this->submit_func == 'edit-document-tracker') {
            $this->documentTracker->tracking_number = $this->tracking_number;
            $this->documentTracker->requestor_name = $this->requestor_name;
            $this->documentTracker->requestor_email = $this->requestor_email;
            $this->documentTracker->requesting_office_id = $this->requesting_office_id ?: null;
            $this->documentTracker->current_office_id = $this->current_office_id;
            $this->documentTracker->document_type = $this->document_type;
            $this->documentTracker->details = $this->details;
            $this->documentTracker->status = $this->status;

            $this->documentTracker->save();

            session()->flash('message', 'Document tracker successfully updated.');
        }

        return redirect()->route('document-trackers');
    }

    public function submit_transfer_document_tracker()
    {
        $this->validate($this->transferRules());

        if (!$this->documentTracker) {
            session()->flash('error', 'Document tracker not found.');
            return redirect()->route('document-trackers');
        }

        if ((int) $this->target_office_id === (int) $this->documentTracker->current_office_id) {
            session()->flash('error', 'The selected office is already the current office.');
            return redirect()->route('document-trackers');
        }

        $action = $this->transfer_action === 'return' ? 'returned' : 'transmitted';

        DB::transaction(function () use ($action) {
            DB::table('document_tracker_logs')->insert([
                'document_tracker_id' => $this->documentTracker->id,
                'from_office_id' => $this->documentTracker->current_office_id,
                'to_office_id' => $this->target_office_id,
                'user_id' => Auth::id(),
                'action' => $action,
                'notes' => $this->transfer_notes,
                'created_at' => now(),
            ]);

            $this->documentTracker->current_office_id = $this->target_office_id;
            $this->documentTracker->released_by_user_id = Auth::id();
            $this->documentTracker->released_at = now();
            $this->documentTracker->status = $action;
            $this->documentTracker->save();
        });

        // Send email notification to requestor if email provided
        if (!empty($this->documentTracker->requestor_email)) {
            try {
                Mail::to($this->documentTracker->requestor_email)->send(new DocumentTrackerTransmittedNotification($this->documentTracker));
            } catch (\Exception $e) {
                Log::error('Failed to send document tracker transmitted email: ' . $e->getMessage());
            }
        }

        session()->flash('message', 'Document tracker successfully ' . $action . '.');

        return redirect()->route('document-trackers');
    }

    public function deleteDocumentTracker($documentTrackerId)
    {
        $documentTracker = DocumentTracker::find($documentTrackerId);

        if (!$documentTracker) {
            session()->flash('error', 'Document tracker not found.');
            return redirect()->route('document-trackers');
        }

        $documentTracker->delete();

        session()->flash('message', 'Document tracker successfully deleted.');

        return redirect()->route('document-trackers');
    }
}