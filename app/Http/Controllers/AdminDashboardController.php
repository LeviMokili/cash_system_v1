<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    //
    public function index()
    {
        // Statistics
        $totalTransfers = Transfer::count();
        $pendingTransfers = Transfer::where('status', 'Pending')->count();
        $completedTransfers = Transfer::where('status', 'Confirmed')->count();
        $rejectedTransfers = Transfer::where('status', 'Cancelled')->count();
        $totalUsers = User::count();

        // Today's transfer amount
        $todayAmount = Transfer::whereDate('date_transfer', today())
            ->where('status', 'Confirmed')
            ->sum('amount');

        // Monthly transfer data (last 6 months)
        // Alternative approach for monthly data
        $monthlyData = Transfer::where('date_transfer', '>=', now()->subMonths(6))
            ->where('status', 'Confirmed')
            ->get()
            ->groupBy(function ($transfer) {
                return $transfer->date_transfer->format('M Y');
            })
            ->map(function ($monthTransfers) {
                return $monthTransfers->sum('amount');
            })
            ->sortKeys(); // This will sort by the month keys

        $months = array_keys($monthlyData->toArray());
        $amounts = array_values($monthlyData->toArray());

        // $months = $monthlyData->pluck('month_label')->toArray();
        // $amounts = $monthlyData->pluck('total_amount')->toArray();

        // Recent data
        $recentLogs = AuditLog::with('user')
            ->latest()
            ->limit(8)
            ->get();

        $recentTransfers = Transfer::latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalTransfers',
            'pendingTransfers',
            'completedTransfers',
            'rejectedTransfers',
            'totalUsers',
            'todayAmount',
            'months',
            'amounts',
            'recentLogs',
            'recentTransfers'
        ));
    }
}
