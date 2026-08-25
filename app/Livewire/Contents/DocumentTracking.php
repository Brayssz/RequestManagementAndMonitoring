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
            ->with([
                'currentOffice',
                'receivedByUser',
                'logs' => function ($query) {
                    $query->with(['fromOffice', 'toOffice', 'user'])
                        ->orderBy('created_at', 'asc');
                },
            ])
            ->orderBy('received_at', 'desc');

        $documentTrackers = $documentTrackers->paginate(12, ['*'], 'page', $page);
        $documentTrackers->getCollection()->transform(function ($documentTracker) {
            $documentTracker->current_office_name = $documentTracker->currentOffice->name ?? 'N/A';

            $movementLogs = $documentTracker->logs->map(function ($log) {
                return [
                    'action' => $log->action,
                    'from_office' => $log->fromOffice->name ?? 'Origin',
                    'to_office' => $log->toOffice->name ?? 'N/A',
                    'user' => $log->user->name ?? 'System',
                    'notes' => $log->notes,
                    'created_at' => $log->created_at
                        ?->copy()
                        ->setTimezone(config('app.display_timezone'))
                        ->toIso8601String(),
                ];
            })->values();

            // Prepend the initial "created" event derived from the tracker itself.
            $originOffice = optional(optional($documentTracker->logs->first())->fromOffice)->name
                ?? $documentTracker->current_office_name;

            $movementLogs->prepend([
                'action' => 'received',
                'from_office' => 'Origin',
                'to_office' => $originOffice,
                'user' => $documentTracker->receivedByUser->name ?? 'System',
                'notes' => 'Document tracker received.',
                'created_at' => $documentTracker->created_at
                    ?->copy()
                    ->setTimezone(config('app.display_timezone'))
                    ->toIso8601String(),
            ]);

            $documentTracker->movement_logs = $movementLogs->values();

            unset($documentTracker->logs);

            return $documentTracker;
        });

        return response()->json($documentTrackers);
    }

    public function render()
    {
        return view('livewire.contents.document-tracking');
    }
}
