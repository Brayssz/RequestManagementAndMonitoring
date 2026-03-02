<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;
use App\Models\FundSource;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel; // Avoid conflict with the global Request class
use App\Models\RequestingOffice;
use App\Models\Requestor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\RequestActivityLog;

class AppController extends Controller
{
    public function index()
    {
        $totalRequests = RequestModel::count();
        $totalPendingRequests = RequestModel::where('status', 'pending')->count();
        $totalTransmittedRequests = RequestModel::where('status', 'transmitted')->count();
        $totalReturnedRequests = RequestModel::where('status', 'returned')->count();
        return view('welcome', compact('totalRequests', 'totalPendingRequests', 'totalTransmittedRequests', 'totalReturnedRequests'));
    }

    public function showMonthlyRequests(Request $request){
        $year = $request->query('year', now()->year);

        $pending = RequestModel::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("COUNT(*) as total")
            )
            ->where('status', 'pending')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $transmitted = RequestActivityLog::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("COUNT(*) as total")
            )
            ->where('activity', 'Transmitted')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $returned = RequestActivityLog::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("COUNT(*) as total")
            )
            ->where('activity', 'Returned')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = range(1, 12);
        $pendingData = [];
        $transmittedData = [];
        $returnedData = [];

        foreach ($months as $month) {
            $pendingData[] = $pending[$month] ?? 0;
            $transmittedData[] = $transmitted[$month] ?? 0;
            $returnedData[] = $returned[$month] ?? 0;
        }

        return response()->json([
            'pending' => $pendingData,
            'transmitted' => $transmittedData,
            'returned' => $returnedData,
        ]);
    }

    public function showDashboard(Request $request)
    {
        $year = $request->query('year', now()->year);

        $availableYears = RequestModel::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (!in_array((int) $year, $availableYears) && !empty($availableYears)) {
            $availableYears[] = (int) $year;
            rsort($availableYears);
        }

        if (empty($availableYears)) {
            $availableYears = [(int) now()->year];
        }

        $totalUsers = User::where('status', 'active')->count();
        $totalOffices = RequestingOffice::where('status', 'active')->where('type', 'office')->count();
        $totalRequestors = Requestor::where('status', 'active')->count();
        $totalSchools = RequestingOffice::where('status', 'active')->where('type', 'school')->count();
        $totalFundSources = FundSource::where('status', 'active')->count();

        $totalPendingRequests = RequestModel::where('status', 'pending')->whereYear('created_at', $year)->count();
        $totalTransmittedRequests = RequestModel::where('status', 'transmitted')->whereYear('created_at', $year)->count();
        $totalReturnedRequests = RequestModel::where('status', 'returned')->whereYear('created_at', $year)->count();

        $pendingRequest = RequestModel::where('status', 'pending')->whereYear('created_at', $year)->limit(10)->get();

        return view('contents.dashboard', compact(
            'totalUsers',
            'totalOffices',
            'totalRequestors',
            'totalSchools',
            'totalFundSources',
            'totalPendingRequests',
            'totalTransmittedRequests',
            'totalReturnedRequests',
            'pendingRequest',
            'availableYears',
            'year'
        ));
    }
}
