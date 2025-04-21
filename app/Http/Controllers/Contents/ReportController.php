<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FundSource;

class ReportController extends Controller
{
    public function generateMonthlySummary(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\AnnualAllotment::query()->with(['requestingOffice', 'fundSource', 'requests']);

            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            if ($request->filled('fund_source_id')) {
                $query->where('fund_source_id', $request->fund_source_id);
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

            $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'allotment_id';
            $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $annualAllotments = $query->skip($start)->take($length)->get();

            $summary = $annualAllotments->map(function ($allotment) {
            $months = collect([
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ]);

            $monthlyData = $months->mapWithKeys(function ($month, $index) use ($allotment) {
                $monthlyRequests = $allotment->requests->filter(function ($request) use ($index) {
                return \Carbon\Carbon::parse($request->dts_date)->month === $index + 1;
                });

                $monthlyAmount = $monthlyRequests->sum(function ($request) {
                return $request->utilize_funds ?? $request->amount;
                });

                return [$month => $monthlyAmount ?: 0];
            });

            $totalAmount = $allotment->requests->sum(function ($request) {
                return $request->utilize_funds ?? $request->amount;
            });

            return [
                'school_name' => $allotment->requestingOffice->name,
                'year' => $allotment->year,
                'fund_source' => $allotment->fundSource->name,
                'allotment_amount' => $allotment->amount,
                'monthly_request_amount' => $monthlyData,
                'total_amount' => $totalAmount,
                'balance' => $allotment->balance,
            ];
            });

            return response()->json([
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $summary
            ]);
        }

        $fund_sources = FundSource::where('status', 'active')->get();

        return view('contents.monthly-summary-report', compact('fund_sources'));
    }

    public function requestHistoryReport(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Request::query()->with(['requestingOffice.requestor_obj', 'fundSource']);

            if ($request->filled('fund_source_id')) {
                $query->where('fund_source_id', $request->fund_source_id);
            }
            if ($request->filled('month')) {
                $query->whereMonth('dts_date', '=', intval($request->month));
            }

            if ($request->filled('year')) {
                $query->whereYear('dts_date', '=', intval($request->year));
            }

            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('dts_tracker_number', 'like', '%' . $search . '%')
                      ->orWhereHas('requestingOffice', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('fundSource', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('requestingOffice.requestor', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
                });
            }

            $totalRecords = $query->count();

            $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'dts_date';
            $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $requests = $query->skip($start)->take($length)->get();

            $data = $requests->map(function ($request) {
                return [
                    'dts_date' => $request->dts_date,
                    'dts_tracker_number' => $request->dts_tracker_number,
                    'sgod_date_received' => $request->sgod_date_received,
                    'requestor' => $request->requestingOffice->requestor_obj->name ?? null,
                    'requesting_office' => $request->requestingOffice->name ?? null,
                    'fund_source' => $request->fundSource->name ?? null,
                    'amount' => $request->amount,
                    'utilize_amount' => $request->utilize_funds ?? $request->amount,
                    'nature_of_request' => $request->nature_of_request,
                    'date_transmitted' => $request->date_transmitted,
                    'remarks' => $request->remarks,
                ];
            });

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $data
            ]);
        }
        $fund_sources = FundSource::where('status', 'active')->get();

        return view('contents.request-history-report', compact('fund_sources'));
    }
}
