<?php

namespace App\Livewire\Contents;

use App\Models\FundSource;
use Livewire\Component;

class FundSourcesManagement extends Component
{
    public $submit_func;

    public $fundSource;

    public $fund_source_id, $name, $status;

    public function getFundSource($fundSourceId)
    {
        $this->fundSource = FundSource::find($fundSourceId);

        // dd($this->fund_source_id);
        if ($this->fundSource) {
            $this->fund_source_id = $this->fundSource->fund_source_id ;
            $this->name = $this->fundSource->name;
            $this->status = $this->fundSource->status;
        } else {
            session()->flash('error', 'Fund Source not found.');
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'nullable|string|max:255',
        ];
    }

    public function render()
    {
        return view('livewire.contents.fund-sources-management');
    }

    public function resetFields()
    {
        $this->reset([
            'name', 'status'
        ]);
    }

    public function submit_fund_source()
    {
        $this->validate();

        if ($this->submit_func == "add-fund-source") {
            FundSource::create([
                'name' => $this->name,
                'status' => $this->status ?? 'active',
            ]);

            session()->flash('message', 'Fund Source successfully created.');
        } elseif ($this->submit_func == "edit-fund-source") {
            $this->fundSource->name = $this->name;
            $this->fundSource->status = $this->status;

            $this->fundSource->save();

            session()->flash('message', 'Fund Source successfully updated.');
        }

        return redirect()->route('fund-sources');
    }
}
