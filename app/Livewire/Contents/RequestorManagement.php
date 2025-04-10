<?php

namespace App\Livewire\Contents;

use App\Models\Requestor;
use Livewire\Component;
use Illuminate\Validation\Rule;

class RequestorManagement extends Component
{
    public $submit_func;

    public $requestor;

    public $requestor_id, $name, $email, $position, $status;

    public function getRequestor($requestorId)
    {
        $this->requestor = Requestor::find($requestorId);

        if ($this->requestor) {
            $this->requestor_id = $this->requestor->requestor_id;
            $this->name = $this->requestor->name;
            $this->email = $this->requestor->email;
            $this->position = $this->requestor->position;
            $this->status = $this->requestor->status;
        } else {
            session()->flash('error', 'Requestor not found.');
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('requestors', 'email')->ignore($this->requestor_id, 'requestor_id'),
            ],
            'position' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ];
    }

    public function render()
    {
        return view('livewire.contents.requestor-management');
    }

    public function resetFields()
    {
        $this->reset([
            'name', 'email', 'position', 'status'
        ]);
    }

    public function submit_requestor()
    {
        $this->validate();

        if ($this->submit_func == "add-requestor") {
            Requestor::create([
                'name' => $this->name,
                'email' => $this->email,
                'position' => $this->position,
                'status' => 'active',
            ]);

            session()->flash('message', 'Requestor successfully created.');
        } elseif ($this->submit_func == "edit-requestor") {
            $this->requestor->name = $this->name;
            $this->requestor->email = $this->email;
            $this->requestor->position = $this->position;
            $this->requestor->status = $this->status;

            $this->requestor->save();

            session()->flash('message', 'Requestor successfully updated.');
        }

        return redirect()->route('requestors');
    }
}
