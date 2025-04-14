<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnnualAllotment;

class AllotmentController extends Controller
{
    public function showAllotments(Request $request)
    {
        if ($request->ajax()) {
            $query = AnnualAllotment::query()->with('requestingOffice', 'requestingOffice.requestor', 'fundSource');

            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('amount', 'like', '%' . $search . '%')
                        ->orWhereHas('requestingOffice', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                });
            }

            $totalRecords = $query->count();

            $orderColumnIndex = $request->input('order')[2]['column'] ?? 2;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'allotment_id';
            $orderDirection = $request->input('order')[2]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $allotments = $query->skip($start)->take($length)->get();

            $allotments->transform(function ($allotment) {
                return $allotment;
            });

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $allotments
            ]);
        }

        return view('contents.allotments');
    }
}
