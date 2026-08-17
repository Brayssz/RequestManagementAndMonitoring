<?php

namespace App\Livewire\Contents;

use App\Models\DocumentTracker;
use Livewire\Component;

class DocumentTracking extends Component
{
    public function getDocumentTrackers($page = 1, $searchQuery = '')
    {
        $search = trim($searchQuery);

        $documentTrackers = DocumentTracker::where(function ($query) use ($search) {
                $query->where('tracking_number', 'like', "%{$search}%")
                    ->orWhere('requestor_name', 'like', "%{$search}%")
                    ->orWhere('document_type', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhereHas('currentOffice', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })
            ->with(['currentOffice'])
            ->orderBy('received_at', 'desc');

        $documentTrackers = $documentTrackers->paginate(12, ['*'], 'page', $page);
        $documentTrackers->getCollection()->transform(function ($documentTracker) {
            $documentTracker->current_office_name = $documentTracker->currentOffice->name ?? 'N/A';
            return $documentTracker;
        });

        return response()->json($documentTrackers);
    }

    public function render()
    {
        return view('livewire.contents.document-tracking');
    }
}
