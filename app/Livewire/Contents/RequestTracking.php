<?php

namespace App\Livewire\Contents;

use Livewire\Component;
use App\Models\Request;

class RequestTracking extends Component
{
    public $totalRequests;
    public $totalPendingRequests;
    public $totalTransmittedRequests;
    public $totalReturnedRequests;

    public function getRequest($page = 1, $searchQuery = '')
    {
        $search = trim($searchQuery);

        $requests = Request::where(function ($query) use ($search) {
                $query->where('dts_tracker_number', 'like', "%{$search}%")
                      ->orWhere('nature_of_request', 'like', "%{$search}%")
                      ->orWhereHas('requestingOffice', function ($query) use ($search) {
                          $query->where('name', 'like', "%{$search}%");
                      });
            })
            ->with(['requestingOffice', 'fundSource', 'transmittedOffice'])
            ->orderBy('dts_date', 'desc');

        $requests = $requests->paginate(12, ['*'], 'page', $page);
        $requests->getCollection()->transform(function ($request) {
            $activityLogs = $request->activityLogs()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($log) {
            $office = $log->transmittedOffice ?? "";
            $officeInfo = $office ? " (" . $log->activity . " to: " . $office->name . ", " . $log->remarks . ")" : "";
            return "📌 " . \Carbon\Carbon::parse($log->created_at)->format('F j, Y') . "\n" . $log->activity . $officeInfo . "\n➜";
            });

            $request->timeline = implode("\n", $activityLogs->toArray());
            return $request;
        });

        return response()->json($requests);
    }

    public function mount()
    {
        
    }
    public function render()
    {
        return view('livewire.contents.request-tracking');
    }
}
