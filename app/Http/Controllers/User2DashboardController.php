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
        return view('user2.dashboard');
    }

  
}
