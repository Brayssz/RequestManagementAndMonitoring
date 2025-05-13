<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel; // Avoid conflict with the global Request class

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

    public function showDashboard()
    {
        return view('contents.dashboard');
    }
}
