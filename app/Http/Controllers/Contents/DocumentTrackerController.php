<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;
use App\Models\DocumentTracker;
use Illuminate\Http\Request as HttpRequest;

class DocumentTrackerController extends Controller
{
    public function showDocumentTrackers(HttpRequest $request)
    {
        if ($request->ajax()) {
            $query = DocumentTracker::query()->with('currentOffice');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $search = $request->input('search_value');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('tracking_number', 'like', '%' . $search . '%')
                        ->orWhere('requestor_name', 'like', '%' . $search . '%')
                        ->orWhere('document_type', 'like', '%' . $search . '%')
                        ->orWhere('details', 'like', '%' . $search . '%')
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
        $totalReturnedDocumentTrackers = DocumentTracker::where('status', 'returned')->count();

        return view('contents.document-trackers', compact(
            'totalDocumentTrackers',
            'totalPendingDocumentTrackers',
            'totalForwardedDocumentTrackers',
            'totalReturnedDocumentTrackers'
        ));
    }
}