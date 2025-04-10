<?php

namespace App\Livewire\Contents;

use App\Models\AnnualAllotment;
use App\Models\RequestingOffice;
use Livewire\Component;

class AllotmentManagement extends Component
{
    public $submit_func;

    public $annualAllotment;

    public $allotment_id, $requesting_office_id, $amount, $year, $status;

    public $requestingOffices;

    public function getAnnualAllotment($allotmentId)
    {
        $this->annualAllotment = AnnualAllotment::find($allotmentId);

        if ($this->annualAllotment) {
            $this->allotment_id = $this->annualAllotment->allotment_id;
            $this->requesting_office_id = $this->annualAllotment->requesting_office_id;
            $this->amount = $this->annualAllotment->amount;
            $this->year = $this->annualAllotment->year;
            $this->status = $this->annualAllotment->status;
        } else {
            session()->flash('error', 'Annual Allotment not found.');
        }
    }

    protected function rules()
    {
        return [
            'requesting_office_id' => [
                'required',
                'exists:requesting_offices,requesting_office_id',
                function ($attribute, $value, $fail) {
                    $exists = AnnualAllotment::where('requesting_office_id', $value)
                        ->where('year', $this->year)
                        ->when($this->annualAllotment, function ($query) {
                            $query->where('allotment_id', '!=', $this->annualAllotment->allotment_id);
                        })
                        ->exists();

                    if ($exists) {
                        $fail('An allotment for this office already exists for the selected year.');
                    }
                },
            ],
            'amount' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'status' => 'nullable|string|max:255',
        ];
    }

    public function mount()
    {
        $this->requestingOffices = RequestingOffice::where('status', 'active')->get();
    }

    public function render()
    {
        return view('livewire.contents.allotment-management');
    }

    public function resetFields()
    {
        $this->reset([
            'requesting_office_id', 'amount', 'year', 'status'
        ]);
    }

    public function submit_annual_allotment()
    {
        $this->validate();

        if ($this->submit_func == "add-annual-allotment") {
            AnnualAllotment::create([
                'requesting_office_id' => $this->requesting_office_id,
                'amount' => $this->amount,
                'year' => $this->year,
                'status' => 'active',
            ]);

            session()->flash('message', 'Annual Allotment successfully created.');
        } elseif ($this->submit_func == "edit-annual-allotment") {
            $this->annualAllotment->requesting_office_id = $this->requesting_office_id;
            $this->annualAllotment->amount = $this->amount;
            $this->annualAllotment->year = $this->year;
            $this->annualAllotment->status = $this->status;

            $this->annualAllotment->save();

            session()->flash('message', 'Annual Allotment successfully updated.');
        }

        return redirect()->route('allotments');
    }
}
