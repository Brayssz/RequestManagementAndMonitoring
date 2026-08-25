<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;
use App\Models\DocumentTracker;
use App\Models\RequestingOffice;
use Illuminate\Http\Request as HttpRequest;

class DocumentTrackerController extends Controller
{
    public function showDocumentTrackers(HttpRequest $request)
    {
        if ($request->ajax()) {
            $query = DocumentTracker::query()->with(['requestingOffice', 'currentOffice']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('requesting_office_id')) {
                // Trackers with no linked office fall back to the requestor name in the
                // table, so they are filtered as their own "External" bucket.
                $request->requesting_office_id === 'external'
                    ? $query->whereNull('requesting_office_id')
                    : $query->where('requesting_office_id', $request->requesting_office_id);
            }

            if ($request->filled('current_office_id')) {
                $query->where('current_office_id', $request->current_office_id);
            }

            $search = $request->input('search_value');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('tracking_number', 'like', '%' . $search . '%')
                        ->orWhere('requestor_name', 'like', '%' . $search . '%')
                        ->orWhere('document_type', 'like', '%' . $search . '%')
                        ->orWhere('details', 'like', '%' . $search . '%')
                        ->orWhereHas('requestingOffice', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('currentOffice', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            }

            $totalRecords = $query->count();

            $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'id';
            $orderDirection = $request->input('order')[0]['dir'] ?? 'desc';

            $query->orderBy('created_at', 'desc')->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $documentTrackers = $query->skip($start)->take($length)->get();

            $documentTrackers->transform(function ($documentTracker) {
                return $documentTracker;
            });

            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $documentTrackers,
            ]);
        }

        $totalDocumentTrackers = DocumentTracker::count();
        $totalPendingDocumentTrackers = DocumentTracker::where('status', 'pending')->count();
        $totalForwardedDocumentTrackers = DocumentTracker::where('status', 'transmitted')->count();
        $totalCompletedDocumentTrackers = DocumentTracker::where('status', 'completed')->count();

        $offices = RequestingOffice::where('status', 'active')->orderBy('name', 'asc')->get();

        return view('contents.document-trackers', compact(
            'totalDocumentTrackers',
            'totalPendingDocumentTrackers',
            'totalForwardedDocumentTrackers',
            'totalCompletedDocumentTrackers',
            'offices'
        ));
    }
}