<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User2 Dashboard - Confirm Transfers</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* === General Layout === */
        body {
            font-family: "Poppins", sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .dashboard-container {
            width: 95%;
            max-width: 1200px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            padding: 30px 40px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        /* === Header === */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        header h2 {
            color: #0d47a1;
        }

        .header-actions a {
            background: #f44336;
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: 600;
            transition: background 0.3s;
        }

        .header-actions a:hover {
            background: #c62828;
        }

        /* === Widgets === */
        .summary-widgets {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .widget {
            background: #f8f9fc;
            border-radius: 10px;
            text-align: center;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .widget:hover {
            transform: translateY(-3px);
        }

        .widget h3 {
            margin: 0;
            font-size: 26px;
            color: #0d47a1;
        }

        .widget p {
            color: #555;
            margin-top: 6px;
            font-weight: 500;
        }

        /* === Table Section === */
        .dashboard-content {
            margin-bottom: 40px;
        }

        .dashboard-content h3 {
            color: #0d47a1;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        }

        .dashboard-table th, .dashboard-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }

        .dashboard-table th {
            background: #1976d2;
            color: #fff;
            text-transform: uppercase;
            font-size: 14px;
        }

        .dashboard-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        /* === Status Badges === */
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: capitalize;
            font-size: 13px;
        }

        .status.Pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.Confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status.Cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        /* === Buttons === */
        .btn-success, .btn-danger, .btn-print {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-success {
            background: #4caf50;
            color: white;
        }

        .btn-success:hover {
            background: #2e7d32;
        }

        .btn-danger {
            background: #f44336;
            color: white;
        }

        .btn-danger:hover {
            background: #c62828;
        }

        .btn-print {
            background: #2196f3;
            color: white;
        }

        .btn-print:hover {
            background: #1565c0;
        }

        /* === Responsive === */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .dashboard-table th, .dashboard-table td {
                font-size: 13px;
            }
            .btn-success, .btn-danger, .btn-print {
                padding: 5px 8px;
                font-size: 12px;
            }
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <header>
            <h2><i class="fas fa-user-check"></i> Welcome, {{ auth()->user()->name }} (Confirmation Officer)</h2>
            <div class="header-actions">
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: #f44336; color: white; border: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.3s;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Summary Widgets -->
        <section class="summary-widgets">
            <div class="widget">
                <h3>{{ $pendingTransfers->count() }}</h3>
                <p>Pending Transfers</p>
            </div>
            <div class="widget">
                <h3>{{ $recentTransfers->where('status', 'Confirmed')->count() }}</h3>
                <p>Confirmed</p>
            </div>
            <div class="widget">
                <h3>{{ $recentTransfers->where('status', 'Cancelled')->count() }}</h3>
                <p>Cancelled</p>
            </div>
        </section>

        <!-- Pending Transfers -->
        <section class="dashboard-content">
            <h3><i class="fas fa-hourglass-half"></i> Pending Transfers</h3>
            @if($pendingTransfers->count() > 0)
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Amount</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingTransfers as $transfer)
                            <tr>
                                <td>{{ $transfer->date_transfer->format('Y-m-d') }}</td>
                                <td>{{ $transfer->reference_code }}</td>
                                <td>{{ $transfer->sender_name }}</td>
                                <td>{{ $transfer->receiver_name }}</td>
                                <td>${{ number_format($transfer->amount, 2) }}</td>
                                <td>{{ $transfer->ville_provenance }}</td>
                                <td>{{ $transfer->ville_destination }}</td>
                                <td>
                                    <a href="{{ route('transfers.confirm', ['id' => $transfer->id, 'action' => 'approve']) }}" clea>✔ Approve</a>
                                    <a href="{{ route('transfers.confirm', ['id' => $transfer->id, 'action' => 'reject']) }}" class="btn-danger">✖ Reject</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No pending transfers found.</p>
            @endif
        </section>

        <!-- Recent Processed Transfers -->
        <section class="dashboard-content">
            <h3><i class="fas fa-check-circle"></i> Recently Processed Transfers</h3>
            @if($recentTransfers->count() > 0)
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Status</th>
                            <th>Print</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransfers as $transfer)
                            <tr>
                                <td>{{ $transfer->date_transfer->format('Y-m-d') }}</td>
                                <td>{{ $transfer->reference_code }}</td>
                                <td>{{ $transfer->sender_name }}</td>
                                <td>{{ $transfer->receiver_name }}</td>
                                <td><span class="status {{ $transfer->status }}">{{ $transfer->status }}</span></td>
                                <td>
                                    @if($transfer->status === 'Confirmed')
                                        <a href="{{ route('transfers.print', $transfer->id) }}" target="_blank" class="btn-print">🖨️ Print</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No recent transfers found.</p>
            @endif
        </section>
    </div>
</body>
</html>