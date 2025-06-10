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

    public function showMonthlyRequests(){
        $pending = RequestModel::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("COUNT(*) as total")
            )
            ->where('status', 'pending')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $transmitted = RequestActivityLog::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("COUNT(*) as total")
            )
            ->where('activity', 'Transmitted')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $returned = RequestActivityLog::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("COUNT(*) as total")
            )
            ->where('activity', 'Returned')
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

    public function showDashboard()
    {


        $totalUsers = User::where('status', 'active')->count();
        $totalOffices = RequestingOffice::where('status', 'active')->where('type', 'office')->count();
        $totalRequestors = Requestor::where('status', 'active')->count();
        $totalSchools = RequestingOffice::where('status', 'active')->where('type', 'school')->count();
        $totalFundSources = FundSource::where('status', 'active')->count();

        $totalPendingRequests = RequestModel::where('status', 'pending')->count();
        $totalTransmittedRequests = RequestModel::where('status', 'transmitted')->count();
        $totalReturnedRequests = RequestModel::where('status', 'returned')->count();

        $pendingRequest = RequestModel::where('status', 'pending')->limit(10)->get();

        return view('contents.dashboard', compact(
            'totalUsers',
            'totalOffices',
            'totalRequestors',
            'totalSchools',
            'totalFundSources',
            'totalPendingRequests',
            'totalTransmittedRequests',
            'totalReturnedRequests',
            'pendingRequest'
        ));
    }
}
