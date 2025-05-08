<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;
use App\Models\FundSource;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{


    public function generateMonthlySummary(Request $request)
    {
        $query = \App\Models\RequestingOffice::with(['requests.fundSource']);

        $offices = $query->get();

        $report = $offices->flatMap(function ($office) {
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

            $groupedRequests = $requests->groupBy(function ($request) {
                return $request->allotment_year . '-' . ($request->fundSource->name ?? 'Unknown');
            });

            return $groupedRequests->map(function ($group, $key) {
                $months = collect([
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December'
                ]);

                $monthlyData = $months->mapWithKeys(function ($month, $index) use ($group) {
                    $monthlyRequests = $group->filter(function ($request) use ($index) {
                        return \Carbon\Carbon::parse($request->sgod_date_received)->month === $index + 1;
                    });

                    $monthlyAmount = $monthlyRequests->sum(function ($request) {
                        return $request->utilize_funds ?? $request->amount;
                    });

                    return [$month => $monthlyAmount ?: 0];
                });

                $totalAmount = $group->sum(function ($request) {
                    return $request->utilize_funds ?? $request->amount;
                });

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

        $fundSource = FundSource::where('fund_source_id', $request->fund_source_id)->first();
        $year = $request->year;

        // return response()->json($report);
        $pdf = Pdf::loadView('pdf.monthly-summary-report-pdf', compact('report', 'fundSource', 'year'))
            ->setPaper('legal', 'landscape');

        return $pdf->stream('monthly_summary_report.pdf');
    }


    // public function generateMonthlySummary(Request $request)
    // {
    //     $months = collect([
    //         'January',
    //         'February',
    //         'March',
    //         'April',
    //         'May',
    //         'June',
    //         'July',
    //         'August',
    //         'September',
    //         'October',
    //         'November',
    //         'December'
    //     ]);

    //     $query = \App\Models\AnnualAllotment::with(['requestingOffice', 'fundSource', 'requests']);

    //     if ($request->filled('year')) {
    //         $query->where('year', $request->year);
    //     }

    //     if ($request->filled('fund_source_id')) {
    //         $query->where('fund_source_id', $request->fund_source_id);
    //     }

    //     $annualAllotments = $query->get();

    //     $report = $annualAllotments->map(function ($allotment) use ($months) {
    //         $monthlyData = $months->mapWithKeys(function ($month, $index) use ($allotment) {
    //             $monthlyRequests = $allotment->requests->filter(function ($request) use ($index) {
    //                 return \Carbon\Carbon::parse($request->dts_date)->month === $index + 1;
    //             });

    //             $monthlyAmount = $monthlyRequests->sum(function ($request) {
    //                 return $request->utilize_funds ?? $request->amount;
    //             });

    //             return [$month => $monthlyAmount ?: 0];
    //         });

    //         $totalAmount = $allotment->requests->sum(function ($request) {
    //             return $request->utilize_funds ?? $request->amount;
    //         });

    //         return [
    //             'school_name' => $allotment->requestingOffice->name,
    //             'year' => $allotment->year,
    //             'fund_source' => $allotment->fundSource->name,
    //             'allotment_amount' => $allotment->amount,
    //             'monthly_request_amount' => $monthlyData,
    //             'total_amount' => $totalAmount,
    //             'balance' => $allotment->balance,
    //         ];
    //     });

    //     $fundSource = FundSource::where('fund_source_id', $request->fund_source_id)->first();
    //     $year = $request->year;

    //     // return response()->json($report);
    //     $pdf = Pdf::loadView('pdf.monthly-summary-report-pdf', compact('report', 'fundSource', 'year'))
    //           ->setPaper('legal', 'landscape');

    //     return $pdf->stream('book_requests_report.pdf');
    // }


    public function requestHistoryReport(Request $request)
    {
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

        $requests = $query->get();

        $report = $requests->map(function ($request) {
            return [
                'dts_date' => $request->dts_date,
                'dts_tracker_number' => $request->dts_tracker_number,
                'sgod_date_received' => $request->sgod_date_received,
                'requestor' => $request->requestingOffice->requestor_obj->name ?? null,
                'requesting_office' => $request->requestingOffice->name ?? null,
                'fund_source' => $request->fundSource->name ?? null,
                'amount' => $request->amount,
                'signed_chief_date' => $request->signed_chief_date,
                'transmitted_office' => $request->transmittedOffice->name ?? null,
                'utilize_amount' => $request->utilize_funds ?? $request->amount,
                'nature_of_request' => $request->nature_of_request,
                'date_transmitted' => $request->date_transmitted,
                'remarks' => $request->remarks,
            ];
        });

        $fundSource = FundSource::where('fund_source_id', $request->fund_source_id)->first();
        $year = $request->year;
        $month = $request->filled('month') ? \Carbon\Carbon::create()->month((int) $request->month)->format('F') : null;

        // return response()->json($report);
        $pdf = Pdf::loadView('pdf.request-history-report-pdf', compact('report', 'fundSource', 'year', 'month'))
            ->setPaper('legal', 'landscape');



        return $pdf->stream('request_history_report.pdf');
    }

    public function requestLogsReport(Request $request)
    {
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

        $logs = $query->orderBy('created_at', 'desc')->get();

        $report = $logs->map(function ($log) {
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
                'date_transmitted' => $log->request->date_transmitted ?? null,
                'remarks' => $log->request->remarks ?? null,
                'actioned_by' => $log->user->name ?? null,
                'activity' => $log->activity,
                'log_date' => \Carbon\Carbon::parse($log->created_at)->format('Y-m-d'),
            ];
        });

        $fundSource = FundSource::where('fund_source_id', $request->fund_source_id)->first();
        $year = $request->year;
        $month = $request->filled('month') ? \Carbon\Carbon::create()->month((int) $request->month)->format('F') : null;

        // return response()->json($report);
        $pdf = Pdf::loadView('pdf.request-logs-report-pdf', compact('report', 'fundSource', 'year', 'month'))
            ->setPaper('legal', 'landscape');

        return $pdf->stream('request_logs_report.pdf');
    }
}
