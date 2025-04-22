<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Request as RequestModel;

class RequestController extends Controller
{
    public function showRequests(HttpRequest $request)
    {
        if ($request->ajax()) {
            $query = RequestModel::query()
                ->whereHas('requestingOffice', function ($q) {
                    $q->where('status', 'active');
                })
                ->whereHas('fundSource', function ($q) {
                    $q->where('status', 'active');
                })
                ->where(function ($q) {
                    $q->whereDoesntHave('transmittedOffice', function ($subQuery) {
                        $subQuery->where('status', 'active');
                    })->orWhereNull('transmitted_office_id');
                })
                ->with('allotment' ,'requestingOffice', 'fundSource', 'transmittedOffice', 'requestingOffice.requestor_obj');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('dts_tracker_number', 'like', '%' . $search . '%')
                        ->orWhere('nature_of_request', 'like', '%' . $search . '%')
                        ->orWhere('remarks', 'like', '%' . $search . '%');
                });
            }

            $totalRecords = $query->count();

            $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'request_id';
            $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $requests = $query->skip($start)->take($length)->get();

            $requests->transform(function ($request) {
                return $request;
            });

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $requests
            ]);
        }

        return view('contents.recieve-request');
    }
}
