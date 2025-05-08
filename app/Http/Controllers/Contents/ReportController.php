<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FundSource;
use App\Models\RequestingOffice;

class ReportController extends Controller
{
    public function generateMonthlySummary(Request $request)
    {
        if ($request->ajax()) {
            $query = RequestingOffice::query()->whereHas('requests')->with(['requests.fundSource']);

            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->where('name', 'like', '%' . $search . '%');
            }

            $totalRecords = $query->count();

            $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'name';

            if (!in_array($orderColumn, ['name', 'another_valid_column'])) {
                $orderColumn = 'name';
            }
            $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $offices = $query->skip($start)->take($length)->get();

            $summary = $offices->flatMap(function ($office) {
                $requests = $office->requests;
            
                if (request()->filled('year')) {
                    $requests = $requests->filter(function ($request) {
                        return $request->allotment_year == request('year');
                    });
                }
            
                if (request()->filled('fund_source_id')) {
                    $requests = $requests->filter(function ($request) {
                        return $request->fund_source_id == request('fund_source_id');
                    });
                }

                if (request()->filled('requesting_office_id')) {
                    $requests = $requests->filter(function ($request) {
                        return $request->requesting_office_id == request('requesting_office_id');
                    });
                }

              
            
                $groupedRequests = $requests->groupBy(function ($request) {
                    return $request->allotment_year . '-' . ($request->fundSource->name ?? 'Unknown');
                });
            
                return $groupedRequests->map(function ($group, $key) {
                    $months = collect([
                        'January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'
                    ]);
            
                    $monthlyData = $months->mapWithKeys(function ($month, $index) use ($group) {
                        $monthlyRequests = $group->filter(function ($request) use ($index) {
                            return \Carbon\Carbon::parse($request->sgod_date_received)->month === $index + 1;
                        });
            
                        $monthlyAmount = $monthlyRequests->sum('amount');
            
                        return [$month => $monthlyAmount ?: 0];
                    });
            
                    $totalAmount = $group->sum('amount');
            
                    [$year, $fundSource] = explode('-', $key);
            
                    return [
                        'school_name' => $group->first()->requestingOffice->name,
                        'year' => $year,
                        'fund_source' => $fundSource,
                        'monthly_request_amount' => $monthlyData,
                        'total_amount' => $totalAmount,
                    ];
                })->values();
            });

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $summary
            ]);
        }

        $fund_sources = FundSource::where('status', 'active')->get();
        $offices = RequestingOffice::where('status', 'active')->where('type', 'office')->get();
        $offices_schools = RequestingOffice::where('status', 'active')->get();

        return view('contents.monthly-summary-report', compact('fund_sources', 'offices', 'offices_schools'));
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

            if ($request->filled('requesting_office_id')) {
                $query->where('requesting_office_id', $request->requesting_office_id);
            }

            if ($request->filled('transmitted_office_id')) {
                $query->where('transmitted_office_id', $request->transmitted_office_id);
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
                    'signed_chief_date' => $request->signed_chief_date,
                    'transmitted_office' => $request->transmittedOffice->name ?? null,
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
        $offices = RequestingOffice::where('status', 'active')->where('type', 'office')->get();
        $offices_schools = RequestingOffice::where('status', 'active')->get();
        

        return view('contents.request-history-report', compact('fund_sources', 'offices', 'offices_schools'));
    }

    public function requestLogsReport(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\RequestActivityLog::query()->with(['request.requestingOffice.requestor_obj', 'request.fundSource', 'user']);

            if ($request->filled('fund_source_id')) {
                $query->whereHas('request', function ($q) use ($request) {
                    $q->where('fund_source_id', $request->fund_source_id);
                });
            }
            if ($request->filled('month')) {
                $query->whereHas('request', function ($q) use ($request) {
                    $q->whereMonth('sgod_date_received', '=', intval($request->month));
                });
            }

            if ($request->filled('requesting_office_id')) {
                $query->whereHas('request', function ($q) use ($request) {
                    $q->where('requesting_office_id', $request->requesting_office_id);
                });
            }

            if ($request->filled('transmitted_office_id')) {
                $query->whereHas('request', function ($q) use ($request) {
                    $q->where('transmitted_office_id', $request->transmitted_office_id);
                });
            }

            if ($request->filled('year')) {
                $query->whereHas('request', function ($q) use ($request) {
                    $q->whereYear('sgod_date_received', '=', intval($request->year));
                });
            }

            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('activity', 'like', '%' . $search . '%')
                        ->orWhereHas('request', function ($q) use ($search) {
                            $q->where('dts_tracker_number', 'like', '%' . $search . '%')
                                ->orWhereHas('requestingOffice', function ($q) use ($search) {
                                    $q->where('name', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('fundSource', function ($q) use ($search) {
                                    $q->where('name', 'like', '%' . $search . '%');
                                });
                        })
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                });
            }

            $totalRecords = $query->count();

            $orderColumn = 'created_at';
            $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $logs = $query->skip($start)->take($length)->get();

            $data = $logs->map(function ($log) {
                return [
                    'dts_date' => $log->request->dts_date ?? null,
                    'dts_tracker_number' => $log->request->dts_tracker_number ?? null,
                    'sgod_date_received' => $log->request->sgod_date_received ?? null,
                    'requestor' => $log->request->requestingOffice->requestor_obj->name ?? null,
                    'requesting_office' => $log->request->requestingOffice->name ?? null,
                    'fund_source' => $log->request->fundSource->name ?? null,
                    'amount' => $log->request->amount ?? null,
                    'utilize_amount' => $log->request->utilize_funds ?? $log->request->amount ?? null,
                    'nature_of_request' => $log->request->nature_of_request ?? null,
                    'date_transmitted' => \Carbon\Carbon::parse($log->transmitted_date)->format('Y-m-d')?? null,
                    'transmitted_office' => $log->transmittedOffice->name ?? "-",
                    'remarks' => $log->remarks ?? null,
                    'actioned_by' => $log->user->name ?? null,
                    'activity' => $log->activity,
                    'log_date' => \Carbon\Carbon::parse($log->created_at)->format('Y-m-d'),
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
        $offices = RequestingOffice::where('status', 'active')->where('type', 'office')->get();
        $offices_schools = RequestingOffice::where('status', 'active')->get();

        return view('contents.request-logs-report', compact('fund_sources', 'offices', 'offices_schools'));
    }
}
