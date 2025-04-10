<?php

namespace App\Livewire\Contents;

use App\Models\Request;
use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\RequestingOffice;
use App\Models\FundSource;
use App\Models\AnnualAllotment;

class ReceiveRequest extends Component
{
    public $submit_func;

    public $request;

    public $requestingOffices;

    public $fundSources;
    public $allotments;

    public $request_id, $dts_date, $dts_tracker_number, $sgod_date_received, $requesting_office_id, $amount, $fund_source_id, $allotment_id, $nature_of_request;

    public function getRequest($requestId)
    {
        $this->request = Request::find($requestId);

        if ($this->request) {
            $this->request_id = $this->request->request_id;
            $this->dts_date = $this->request->dts_date;
            $this->dts_tracker_number = $this->request->dts_tracker_number;
            $this->sgod_date_received = $this->request->sgod_date_received;
            $this->requesting_office_id = $this->request->requesting_office_id;
            $this->amount = $this->request->amount;
            $this->fund_source_id = $this->request->fund_source_id;
            $this->allotment_id = $this->request->allotment_id;
            $this->nature_of_request = $this->request->nature_of_request;
        } else {
            session()->flash('error', 'Request not found.');
        }
    }

    

    protected function rules()
    {
        return [
            'dts_date' => 'required|date',
            'dts_tracker_number' => 'required|string|max:255',
            'sgod_date_received' => 'required|date',
            'requesting_office_id' => 'required|integer|exists:requesting_offices,requesting_office_id',
            'amount' => 'required|numeric|min:0',
            'fund_source_id' => 'required|integer|exists:fund_sources,fund_source_id',
            'allotment_id' => 'required|integer|exists:annual_allotments,allotment_id',
            'nature_of_request' => 'required|string|max:255',
        ];
    }

    public function mount() 
    {
        $this->requestingOffices = RequestingOffice::whereHas('annualAllotment')->get();

        $this->fundSources = FundSource::all();
        $this->allotments = AnnualAllotment::all();
    }

    public function render()
    {
        return view('livewire.contents.receive-request');
    }

    public function resetFields()
    {
        $this->reset([
            'dts_date', 'dts_tracker_number', 'sgod_date_received', 'requesting_office_id', 'amount', 'fund_source_id', 'allotment_id', 'nature_of_request'
        ]);
    }

    public function submit_request()
    {
        $this->validate();

        if ($this->submit_func == "add-request") {
            Request::create([
                'dts_date' => $this->dts_date,
                'dts_tracker_number' => $this->dts_tracker_number,
                'sgod_date_received' => $this->sgod_date_received,
                'requesting_office_id' => $this->requesting_office_id,
                'amount' => $this->amount,
                'fund_source_id' => $this->fund_source_id,
                'allotment_id' => $this->allotment_id,
                'nature_of_request' => $this->nature_of_request,
            ]);

            session()->flash('message', 'Request successfully created.');
        } elseif ($this->submit_func == "edit-request") {
            $this->request->dts_date = $this->dts_date;
            $this->request->dts_tracker_number = $this->dts_tracker_number;
            $this->request->sgod_date_received = $this->sgod_date_received;
            $this->request->requesting_office_id = $this->requesting_office_id;
            $this->request->amount = $this->amount;
            $this->request->fund_source_id = $this->fund_source_id;
            $this->request->allotment_id = $this->allotment_id;
            $this->request->nature_of_request = $this->nature_of_request;

            $this->request->save();

            session()->flash('message', 'Request successfully updated.');
        }

        return redirect()->route('requests');
    }
}
