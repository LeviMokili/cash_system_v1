<?php
// app/Http/Controllers/User1DashboardController.php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class User1DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $today = Carbon::today()->toDateString();

        $stats = [
            'totalToday' => Transfer::where('created_by', $userId)
                ->whereDate('created_at', $today)
                ->count(),

            'pendingCount' => Transfer::where('created_by', $userId)
                ->where('status', 'Pending')
                ->count(),

            'confirmedCount' => Transfer::where('created_by', $userId)
                ->where('status', 'Confirmed')
                ->count(),

            'cancelledCount' => Transfer::where('created_by', $userId)
                ->where('status', 'Cancelled')
                ->count(),
        ];

        $recentTransfers = Transfer::where('created_by', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(50) // Increased for DataTable
            ->get();

        return view('user1.dashboard', compact('stats', 'recentTransfers'));
    }

    // API endpoint for DataTable (if using server-side processing)
    public function getTransfersData(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $transfers = Transfer::where('created_by', $userId)
            ->select(['id', 'date_transfer', 'reference_code', 'sender_name', 'receiver_name', 'amount', 'ville_provenance', 'ville_destination', 'status', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $transfers]);

    }

    // Remove the duplicate dashboard method, keep only index
}