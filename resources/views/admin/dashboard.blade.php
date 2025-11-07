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
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar (unchanged) -->
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

            <!-- Chart Section with Filters -->
            <section class="chart-container">
                <div class="chart-header">
                    <h3>Transfer Analytics</h3>
                    <div class="chart-filters">
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="period-filter">
                            <button type="submit" name="period" value="weekly" class="filter-btn {{ $period == 'weekly' ? 'active' : '' }}">
                                <i class="fas fa-calendar-week"></i> Weekly
                            </button>
                            <button type="submit" name="period" value="monthly" class="filter-btn {{ $period == 'monthly' ? 'active' : '' }}">
                                <i class="fas fa-calendar-alt"></i> Monthly
                            </button>
                            <button type="submit" name="period" value="yearly" class="filter-btn {{ $period == 'yearly' ? 'active' : '' }}">
                                <i class="fas fa-calendar"></i> Yearly
                            </button>
                        </form>
                    </div>
                </div>
                <canvas id="transferChart" height="120"></canvas>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('transferChart');
            if (!ctx) return;

            const months = @json($months);
            const amounts = @json($amounts);
            const period = @json($period);

            // Determine chart title based on period
            let chartTitle = 'Transfer Amount (USD)';
            let xAxisTitle = 'Period';
            
            switch(period) {
                case 'weekly':
                    chartTitle = 'Weekly Transfer Amount (Last 12 Weeks)';
                    xAxisTitle = 'Weeks';
                    break;
                case 'monthly':
                    chartTitle = 'Monthly Transfer Amount (Last 6 Months)';
                    xAxisTitle = 'Months';
                    break;
                case 'yearly':
                    chartTitle = 'Yearly Transfer Amount (Last 5 Years)';
                    xAxisTitle = 'Years';
                    break;
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
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
                                text: xAxisTitle
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>