<?php

namespace App\Livewire\Contents;

use App\Models\Request;
use App\Models\RequestingOffice;
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
        $request = Request::where('request_id', $requestId)->first();
        $request->update([
            'status' => 'returned',
        ]);

        session()->flash('message', 'Request returned successfully.');
        return redirect()->route('receive-requests');
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

        $request = Request::findOrFail($this->requestId);

        $request->update([
            'signed_chief_date' => $this->signed_chief_date,
            'date_transmitted' => $this->date_transmitted,
            'transmitted_office_id' => $this->transmitted_office_id,
            'remarks' => $this->remarks,
            'status' => 'transmitted',
        ]);

        session()->flash('message', 'Request transmitted successfully.');
        return redirect()->route('receive-requests');
    }

    public function return_request()
    {
        $this->validate([
            'date_transmitted' => 'required|date',
            'transmitted_office_id' => 'required|exists:requesting_offices,requesting_office_id',
            'remarks' => 'nullable|string|max:255',
        ]);

        $request = Request::findOrFail($this->requestId);

        $request->update([
            'date_transmitted' => $this->date_transmitted,
            'transmitted_office_id' => $this->transmitted_office_id,
            'remarks' => $this->remarks,
            'status' => 'returned',
        ]);

        session()->flash('message', 'Request returned successfully.');
        return redirect()->route('receive-requests');
    }

    public function deleteRequest($requestId)
    {
        $request = Request::where('request_id', $requestId)->first();
        $request->delete();

        session()->flash('message', 'Request deleted successfully.');
        return redirect()->route('receive-requests');
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
