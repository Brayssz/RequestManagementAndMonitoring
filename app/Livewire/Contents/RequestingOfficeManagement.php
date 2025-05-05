<?php

namespace App\Livewire\Contents;

use App\Models\RequestingOffice;
use App\Models\Requestor;
use Livewire\Component;

class RequestingOfficeManagement extends Component
{
    public $submit_func;

    public $requestingOffice;

    public $requesting_office_id, $name, $requestor, $status;

    public $requestors;

    public function getRequestingOffice($officeId)
    {
        $this->requestingOffice = RequestingOffice::find($officeId);

        if ($this->requestingOffice) {
            $this->requesting_office_id = $this->requestingOffice->requesting_office_id;
            $this->name = $this->requestingOffice->name;
            $this->requestor = $this->requestingOffice->requestor;
            $this->status = $this->requestingOffice->status;
        } else {
            session()->flash('error', 'Requesting Office not found.');
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'requestor' => 'nullable|exists:requestors,requestor_id',
            'status' => 'nullable|string|max:255',
        ];
    }

    public function mount()
    {
        $this->requestors = Requestor::where('status', 'active')
            ->get();
    }

    public function render()
    {
        return view('livewire.contents.requesting-office-management');
    }

    public function resetFields()
    {
        $this->reset([
            'name', 'requestor', 'status'
        ]);
    }

    public function submit_requesting_office()
    {
        $this->validate();

        if ($this->submit_func == "add-requesting-office") {
            RequestingOffice::create([
                'name' => $this->name,
                'type' => 'office',
                'requestor' => $this->requestor,
                'status' => 'active',
            ]);

            session()->flash('message', 'Requesting Office successfully created.');
        } elseif ($this->submit_func == "edit-requesting-office") {
            $this->requestingOffice->name = $this->name;
            $this->requestingOffice->type = 'office';
            $this->requestingOffice->requestor = $this->requestor;
            $this->requestingOffice->status = $this->status;

            $this->requestingOffice->save();

            session()->flash('message', 'Requesting Office successfully updated.');
        }

        return redirect()->route('requesting-offices');
    }
}
