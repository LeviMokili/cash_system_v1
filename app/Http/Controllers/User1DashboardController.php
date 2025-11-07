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
        // ...existing code...

    $today = Carbon::today()->toDateString();
    $userId = auth()->id();

       
        // existing stats (example)
      $stats['totalToday'] = Transfer::where('created_by', $userId)
        ->whereDate('date_transfer', $today)
        ->count();

    $stats['pendingCount'] = Transfer::where('created_by', $userId)->where('status', 'pending')->count();
    $stats['confirmedCount'] = Transfer::where('created_by', $userId)->where('status', 'confirmed')->count();
    $stats['cancelledCount'] = Transfer::where('created_by', $userId)->where('status', 'cancelled')->count();

    $recentTransfers = Transfer::where('created_by', $userId)
        ->orderBy('date_transfer', 'desc')
        ->take(10)
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