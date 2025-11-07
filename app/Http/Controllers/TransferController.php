<?php
// app/Http/Controllers/TransferController.php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransferController extends Controller
{
    public function create()
    {
        return view('user1.create'); // Store in user1/transfers directory
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'ville_provenance' => 'required|string|max:255',
            'ville_destination' => 'required|string|max:255',
            'guichetier_provenance' => 'required|string|max:255',
            'guichetier_destination' => 'required|string|max:255',
            'date_transfer' => 'required|date',
        ]);

        $referenceCode = $this->generateReferenceCode($validated['date_transfer']);
        Transfer::create([
            'reference_code' => $referenceCode,
            'sender_name' => $validated['sender_name'],
            'receiver_name' => $validated['receiver_name'],
            'amount' => $validated['amount'],
            'ville_provenance' => $validated['ville_provenance'],
            'ville_destination' => $validated['ville_destination'],
            'guichetier_provenance' => $validated['guichetier_provenance'],
            'guichetier_destination' => $validated['guichetier_destination'],
            'date_transfer' => $validated['date_transfer'],
            'status' => 'Pending',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('user1.dashboard')
            ->with('success', 'Transfer created successfully!');
    }


    private function generateReferenceCode($date)
    {
        $date = Carbon::parse($date);

        // Format: YYMMDD (last 2 digits of year, month, day)
        $datePart = $date->format('ymd'); // This gives "251106" for 2025-11-06

        // Count how many transfers already exist for this date
        $count = Transfer::whereDate('date_transfer', $date->format('Y-m-d'))->count();

        // Increment count by 1 for the new transfer
        $sequenceNumber = $count + 1;

        // Format: YYMMDD + N + sequence number (251106N1, 251106N2, etc.)
        return $datePart . 'N' . $sequenceNumber;
    }


    // AJAX method to get reference code for a selected date
    public function getReferenceCode(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $referenceCode = $this->generateReferenceCode($request->date);

        return response()->json(['reference_code' => $referenceCode]);
    }

    public function index()
    {
        $transfers = Transfer::where('created_by', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user1.transfers.index', compact('transfers'));
    }

    public function show($id)
    {
        $transfer = Transfer::where('created_by', auth()->id())->findOrFail($id);
        return view('user1.show', compact('transfer'));
    }




    public function update(Request $request, Transfer $transfer)
    {
        // Check if the transfer belongs to the current user
        if ($transfer->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'date_transfer' => 'required|date',
            'sender_name' => 'required|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'ville_provenance' => 'required|string|max:255',
            'ville_destination' => 'required|string|max:255',
            'guichetier_provenance' => 'required|string|max:255',
            'guichetier_destination' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',

        ]);

        try {
            $transfer->update($validated);

            return redirect()->route('transfers.edit', $transfer)
                ->with('success', 'Transfer updated successfully!');

        } catch (\Exception $e) {
            return redirect()->route('transfers.edit', $transfer)
                ->with('error', 'Error updating transfer: ' . $e->getMessage())
                ->withInput();
        }
    }




    // ADD THIS MISSING EDIT METHOD
    public function edit($id)
    {
        $transfer = Transfer::where('created_by', auth()->id())->findOrFail($id);
        return view('user1.edit', compact('transfer'));
    }







    public function confirmTransfer($id, Request $request)
    {
        $transfer = Transfer::findOrFail($id);
        $action = $request->get('action');

        if ($action === 'approve') {
            $transfer->status = 'Confirmed';
            $transfer->save();
            return redirect()->route('user2.dashboard')->with('success', 'Transfer approved successfully.');
        } elseif ($action === 'reject') {
            $transfer->status = 'Cancelled';
            $transfer->save();
            return redirect()->route('user2.dashboard')->with('success', 'Transfer rejected successfully.');
        }

        return redirect()->route('user2.dashboard')->with('error', 'Invalid action.');
    }




}