<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class User2DashboardController extends Controller
{
    //
     public function index()
    {

         $pendingTransfers = Transfer::where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $recentTransfers = Transfer::whereIn('status', ['Confirmed', 'Cancelled'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('user2.dashboard', compact('pendingTransfers', 'recentTransfers'));

    }
  
}
