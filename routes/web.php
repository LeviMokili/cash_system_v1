<?php
// routes/web.php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User1DashboardController;
use App\Http\Controllers\User2DashboardController;
use App\Http\Controllers\TransferController; // Add this
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        } elseif ($user->isUser1()) {
            return redirect('/user1/dashboard');
        } elseif ($user->isUser2()) {
            return redirect('/user2/dashboard');
        }

        return redirect('/login');
    });

    // Admin Routes
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // User1 Routes
    Route::prefix('user1')->middleware('user1')->group(function () {
        Route::get('/dashboard', [User1DashboardController::class, 'index'])->name('user1.dashboard');
        Route::get('/api/transfers-data', [User1DashboardController::class, 'getTransfersData'])->name('api.transfers.data');

        // Add transfer routes for user1
        Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create');
        Route::post('/transfers', [TransferController::class, 'store'])->name('transfers.store');
        Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');
     

          // Edit/Update Routes - FIXED (added PUT method and show route)
        Route::get('/transfers/{transfer}/edit', [TransferController::class, 'edit'])->name('transfers.edit');
        Route::put('/transfers/{transfer}', [TransferController::class, 'update'])->name('transfers.update');
        Route::get('/transfers/{transfer}', [TransferController::class, 'show'])->name('transfers.show');

        Route::get('/get-reference-code', [TransferController::class, 'getReferenceCode'])->name('transfers.get-reference');
    });

    // User2 Routes
    Route::prefix('user2')->middleware('user2')->group(function () {
        Route::get('/dashboard', [User2DashboardController::class, 'index'])->name('user2.dashboard');
    });
});