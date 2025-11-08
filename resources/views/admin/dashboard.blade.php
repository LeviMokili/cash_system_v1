<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cash Transfer System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <script src="https://kit.fontawesome.com/a2d9d5a64b.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-filters {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .filter-section {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .filter-section h4 {
            margin: 0 0 15px 0;
            color: #2c3e50;
            font-size: 16px;
        }

        .filter-row {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-group select {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            min-width: 150px;
        }

        .filter-group label {
            font-weight: 600;
            color: #495057;
            min-width: 80px;
        }

        .chart-container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .no-chart-message {
            text-align: center;
            color: #7f8c8d;
            font-size: 18px;
        }

        .chart-placeholder {
            width: 100%;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 8px;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-group {
                justify-content: space-between;
            }
            
            .filter-group select {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-coins"></i> Admin</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> Manage Users</a></li>
                <li><a href="{{ route('admin.logs.index') }}"><i class="fas fa-clipboard-list"></i> View Logs</a></li>
                <li><a href="{{ route('admin.transfers.index') }}"><i class="fas fa-exchange-alt"></i> Transfers</a></li>
                <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Settings</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout" style="background: none; border: none; color: inherit; width: 100%; text-align: left; padding: 12px 20px; font-size: 1em; cursor: pointer;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="admin-header">
                <h2>Welcome, {{ auth()->user()->name }} (Administrator)</h2>
            </header>
             <!-- Summary Widgets (unchanged) -->
            <section class="summary-widgets">
                <div class="widget total">
                    <h3>{{ $totalTransfers }}</h3>
                    <p>Total Transfers</p>
                </div>
                <div class="widget pending">
                    <h3>{{ $pendingTransfers }}</h3>
                    <p>Pending</p>
                </div>
                <div class="widget completed">
                    <h3>{{ $completedTransfers }}</h3>
                    <p>Completed</p>
                </div>
                <div class="widget rejected">
                    <h3>{{ $rejectedTransfers }}</h3>
                    <p>Rejected</p>
                </div>
                <div class="widget users">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Registered Users</p>
                </div>
                <div class="widget today">
                    <h3>${{ number_format($todayAmount, 2) }}</h3>
                    <p>Today's Transfers</p>
                </div>
            </section>

            <!-- Summary Widgets -->
            <section class="summary-widgets">
                <!-- Your existing widgets -->
            </section>

            <!-- Chart Filters -->
            <div class="chart-filters">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="period-filter-form">
                    <!-- Period Type Selection -->
                    <div class="filter-section">
                        <h4>📊 Select Chart Type:</h4>
                        <div class="filter-row">
                            <div class="filter-group">
                                <label>Period Type:</label>
                                <select name="period" onchange="this.form.submit()">
                                    <option value="">Select Period Type</option>
                                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily View</option>
                                    <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Weekly View</option>
                                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly View</option>
                                    <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>Yearly View</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Filters Based on Period Type -->
                    @if($period)
                        <div class="filter-section">
                            <h4>🎯 Select Time Period:</h4>
                            <div class="filter-row">
                                <!-- Year Selection (Required for all types) -->
                                <div class="filter-group">
                                    <label>Year:</label>
                                    <select name="year" onchange="this.form.submit()">
                                        <option value="">Select Year</option>
                                        @foreach($availableYears as $year)
                                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Month Selection (Required for Daily, Weekly, Monthly) -->
                                @if(in_array($period, ['daily', 'weekly', 'monthly']) && $selectedYear)
                                    <div class="filter-group">
                                        <label>Month:</label>
                                        <select name="month" onchange="this.form.submit()">
                                            <option value="">Select Month</option>
                                            @foreach($availableMonths as $monthNum => $monthName)
                                                <option value="{{ $monthNum }}" {{ $selectedMonth == $monthNum ? 'selected' : '' }}>
                                                    {{ $monthName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <!-- Week Selection (Required for Weekly only) -->
                                @if($period == 'weekly' && $selectedYear && $selectedMonth)
                                    <div class="filter-group">
                                        <label>Week:</label>
                                        <select name="week" onchange="this.form.submit()">
                                            <option value="">Select Week</option>
                                            @foreach($availableWeeks as $week)
                                                <option value="{{ $week }}" {{ $selectedWeek == $week ? 'selected' : '' }}>
                                                    Week {{ $week }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Chart Container -->
            <section class="chart-container">
                <h3>{{ $chartTitle }}</h3>
                
                @if($showChart)
                    <canvas id="transferChart" height="120"></canvas>
                @else
                    <div class="chart-placeholder">
                        <div class="no-chart-message">
                            <i class="fas fa-chart-bar" style="font-size: 48px; margin-bottom: 20px; opacity: 0.5;"></i>
                            <p>Please select the required filters above to view the chart</p>
                            @if($period == 'daily')
                                <small>Required: Year + Month</small>
                            @elseif($period == 'weekly')
                                <small>Required: Year + Month + Week</small>
                            @elseif($period == 'monthly')
                                <small>Required: Year + Month</small>
                            @elseif($period == 'yearly')
                                <small>Required: Year</small>
                            @endif
                        </div>
                    </div>
                @endif
            </section>

              <!-- Recent Transfers (unchanged) -->
            <section class="dashboard-content">
                <h3>Recent Transfers</h3>
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client Code</th>
                            <th>Sender</th>
                            <th>Recipient</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransfers as $transfer)
                            <tr>
                                <td>{{ $transfer->date_transfer->format('Y-m-d') }}</td>
                                <td>{{ $transfer->reference_code }}</td>
                                <td>{{ $transfer->sender_name }}</td>
                                <td>{{ $transfer->receiver_name }}</td>
                                <td>${{ number_format($transfer->amount, 2) }}</td>
                                <td><span class="status {{ strtolower($transfer->status) }}">{{ ucfirst($transfer->status) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: #7f8c8d;">No transfers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>

<!-- Recent Logs (unchanged) -->
            <section class="dashboard-content">
                <h3>Recent Activities</h3>
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                            <tr>
                                <td>{{ $log->user->name ?? 'N/A' }}</td>
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->details }}</td>
                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #7f8c8d;">No activities found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    @if($showChart)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('transferChart');
            if (!ctx) return;

            const labels = @json($labels ?? []);
            const amounts = @json($amounts ?? []);
            const chartTitle = @json($chartTitle ?? 'Transfer Analytics');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Transfer Amount (USD)',
                        data: amounts,
                        backgroundColor: 'rgba(52, 152, 219, 0.8)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: chartTitle,
                            font: {
                                size: 16
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount (USD)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Period'
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endif
</body>
</html>