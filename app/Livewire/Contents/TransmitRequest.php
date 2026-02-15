<?php

namespace App\Livewire\Contents;

use App\Models\Request;
use App\Models\RequestingOffice;
use App\Services\RequestNotificationService;
use Livewire\Component;

class TransmitRequest extends Component
{
    public $signed_chief_date;
    public $date_transmitted;
    public $transmitted_office_id;
    public $remarks;
    public $requestingOffices = [];
    public $requestId;

    public $status;
    public $returnOffices = [];
    public $isTransmitting = false;
    public $isReturning = false;
    public $isDeleting = false;

    protected $rules = [
        'signed_chief_date' => 'required|date',
        'date_transmitted' => 'required|date',
        'transmitted_office_id' => 'required|exists:requesting_offices,requesting_office_id',
        'remarks' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->requestingOffices = RequestingOffice::where('type', 'office')
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();
        $this->returnOffices = RequestingOffice::where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function returnRequest($requestId)
    {
        $this->isReturning = true;

        try {
            $request = Request::where('request_id', $requestId)->first();
            $request->update([
                'status' => 'returned',
            ]);

            // Send email notification for returned request
            RequestNotificationService::sendReturnedNotification($request);

            session()->flash('message', 'Request returned successfully.');
            return redirect()->route('receive-requests');
        } finally {
            $this->isReturning = false;
        }
    }

    public function getRequest($id)
    {
        $request = Request::findOrFail($id);
        $this->requestId = $request->request_id;
        $this->signed_chief_date = $request->signed_chief_date;
        $this->date_transmitted = $request->date_transmitted;
        $this->transmitted_office_id = $request->transmitted_office_id;
        $this->remarks = $request->remarks;
        $this->status = $request->status;
       
    }
    public function transmit_request()
    {
        $this->validate();
        $this->isTransmitting = true;

        try {
            $request = Request::findOrFail($this->requestId);

            $request->update([
                'signed_chief_date' => $this->signed_chief_date,
                'date_transmitted' => $this->date_transmitted,
                'transmitted_office_id' => $this->transmitted_office_id,
                'remarks' => $this->remarks,
                'status' => 'transmitted',
            ]);

            // Refresh the request to get updated data and send email notification
            $request->refresh();
            RequestNotificationService::sendTransmittedNotification($request);

            session()->flash('message', 'Request transmitted successfully.');
            return redirect()->route('receive-requests');
        } finally {
            $this->isTransmitting = false;
        }
    }

    public function return_request()
    {
        $this->validate([
            'date_transmitted' => 'required|date',
            'transmitted_office_id' => 'required|exists:requesting_offices,requesting_office_id',
            'remarks' => 'nullable|string|max:255',
        ]);

        $this->isReturning = true;

        try {
            $request = Request::findOrFail($this->requestId);

            $request->update([
                'date_transmitted' => $this->date_transmitted,
                'transmitted_office_id' => $this->transmitted_office_id,
                'remarks' => $this->remarks,
                'status' => 'returned',
            ]);

            // Refresh the request to get updated data and send email notification
            $request->refresh();
            RequestNotificationService::sendReturnedNotification($request);

            session()->flash('message', 'Request returned successfully.');
            return redirect()->route('receive-requests');
        } finally {
            $this->isReturning = false;
        }
    }

    public function deleteRequest($requestId)
    {
        $this->isDeleting = true;

        try {
            $request = Request::where('request_id', $requestId)->first();
            
            // Send email notification before deleting the request
            RequestNotificationService::sendDeletedNotification($request);
            
            $request->delete();

            session()->flash('message', 'Request deleted successfully.');
            return redirect()->route('receive-requests');
        } finally {
            $this->isDeleting = false;
        }
    }

    public function resetForm()
    {
        $this->signed_chief_date = null;
        $this->date_transmitted = null;
        $this->transmitted_office_id = null;
        $this->remarks = null;
    }

    public function render()
    {
        return view('livewire.contents.transmit-request');
    }
}
