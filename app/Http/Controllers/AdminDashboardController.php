<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Transfer;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $period = $request->get('period', 'daily');
        $selectedYear = $request->get('year');
        $selectedMonth = $request->get('month');
        $selectedWeek = $request->get('week');

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

        // Get available years for dropdown
        $availableYears = $this->getAvailableYears();

        // Initialize chart data
        $labels = [];
        $amounts = [];
        $chartTitle = 'Select filters to view chart';
        $showChart = false;

        // Only show chart if required filters are selected
        if ($this->hasRequiredFilters($period, $selectedYear, $selectedMonth, $selectedWeek)) {
            $chartData = $this->getChartData($period, $selectedYear, $selectedMonth, $selectedWeek);
            $labels = $chartData['labels'];
            $amounts = $chartData['data'];
            $chartTitle = $chartData['title'];
            $showChart = true;
        }

        // Get available months and weeks based on selected year
        $availableMonths = $selectedYear ? $this->getAvailableMonths($selectedYear) : [];
        $availableWeeks = ($selectedYear && $selectedMonth) ? $this->getAvailableWeeks($selectedYear, $selectedMonth) : [];

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
            'labels',
            'amounts',
            'recentLogs',
            'recentTransfers',
            'period',
            'selectedYear',
            'selectedMonth',
            'selectedWeek',
            'chartTitle',
            'availableYears',
            'availableMonths',
            'availableWeeks',
            'showChart'
        ));
    }

    private function hasRequiredFilters($period, $year, $month, $week)
    {
        return match ($period) {
            'daily' => !empty($year) && !empty($month),
            'weekly' => !empty($year) && !empty($month) && !empty($week),
            'monthly' => !empty($year) && !empty($month),
            'yearly' => !empty($year),
            default => false
        };
    }

    private function getAvailableYears()
    {
        return Transfer::select(DB::raw('YEAR(date_transfer) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
    }

    private function getAvailableMonths($year)
    {
        $months = Transfer::select(DB::raw('MONTH(date_transfer) as month'))
            ->whereYear('date_transfer', $year)
            ->distinct()
            ->orderBy('month')
            ->pluck('month')
            ->toArray();

        // Convert month numbers to names
        $monthNames = [];
        foreach ($months as $month) {
            $monthNames[$month] = DateTime::createFromFormat('!m', $month)->format('F');
        }

        return $monthNames;
    }

    private function getAvailableWeeks($year, $month)
    {
        // Get transfers and calculate week of month
        $transfers = Transfer::whereYear('date_transfer', $year)
            ->whereMonth('date_transfer', $month)
            ->get();

        $weeks = [];

        foreach ($transfers as $transfer) {
            $day = $transfer->date_transfer->day;
            $weekOfMonth = ceil($day / 7);
            $weeks[$weekOfMonth] = $weekOfMonth;
        }

        return array_values($weeks);
    }

    private function getChartData($period, $year, $month, $week)
    {
        switch ($period) {
            case 'daily':
                return $this->getDailyData($year, $month);
            case 'weekly':
                return $this->getWeeklyData($year, $month, $week);
            case 'monthly':
                return $this->getMonthlyData($year, $month);
            case 'yearly':
                return $this->getYearlyData($year);
            default:
                return $this->getDailyData($year, $month);
        }
    }

    private function getDailyData($year, $month)
    {
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();
        $title = "Daily Transfers for {$start->format('F Y')}";

        $data = Transfer::select(
            DB::raw('DAY(date_transfer) as day'),
            DB::raw('DATE_FORMAT(date_transfer, "%b %d") as day_label'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->whereYear('date_transfer', $year)
            ->whereMonth('date_transfer', $month)
            ->where('status', 'Confirmed')
            ->groupBy('day', 'day_label')
            ->orderBy('day')
            ->get();

        return $this->fillDailyGaps($data, $start, $end, $title);
    }

    private function getWeeklyData($year, $month, $week)
    {

        $start = Carbon::create($year, $month, 1);
        $title = "Week $week (Month Week) Transfers for {$start->format('F Y')}"; // ← Changed here

        // Calculate start and end of the selected week
        $firstDayOfMonth = Carbon::create($year, $month, 1);
        $startOfWeek = $firstDayOfMonth->copy()->addWeeks($week - 1)->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        // Ensure we don't go beyond the current month
        if ($endOfWeek->month != $month) {
            $endOfWeek = $firstDayOfMonth->copy()->endOfMonth();
        }

        $data = Transfer::select(
            DB::raw('DATE(date_transfer) as date'),
            DB::raw('DAY(date_transfer) as day'),
            DB::raw('DATE_FORMAT(date_transfer, "%b %d") as day_label'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->whereBetween('date_transfer', [$startOfWeek, $endOfWeek])
            ->where('status', 'Confirmed')
            ->groupBy('date', 'day', 'day_label')
            ->orderBy('date')
            ->get();

        $labels = [];
        $amounts = [];

        $current = $startOfWeek->copy();
        while ($current <= $endOfWeek) {
            $label = $current->format('M d');
            $dayData = $data->firstWhere('day', $current->day);

            $labels[] = $label;
            $amounts[] = $dayData ? (float) $dayData->total_amount : 0;

            $current->addDay();
        }

        return [
            'labels' => $labels,
            'data' => $amounts,
            'title' => $title
        ];
    }

    private function getMonthlyData($year, $month)
    {
        $monthName = DateTime::createFromFormat('!m', $month)->format('F');
        $title = "Monthly Transfers for {$monthName} {$year}";

        // Get total amount for the selected month
        $totalAmount = Transfer::whereYear('date_transfer', $year)
            ->whereMonth('date_transfer', $month)
            ->where('status', 'Confirmed')
            ->sum('amount');

        // For monthly view, we show a single bar for the selected month
        return [
            'labels' => [$monthName],
            'data' => [(float) $totalAmount],
            'title' => $title
        ];
    }

    private function getYearlyData($year)
    {
        $title = "Yearly Transfers for {$year}";

        // Get total amount for the selected year
        $totalAmount = Transfer::whereYear('date_transfer', $year)
            ->where('status', 'Confirmed')
            ->sum('amount');

        // For yearly view, we show a single bar for the selected year
        return [
            'labels' => [$year],
            'data' => [(float) $totalAmount],
            'title' => $title
        ];
    }

    private function fillDailyGaps($data, $start, $end, $title)
    {
        $labels = [];
        $amounts = [];

        $daysInMonth = $end->day;

        // Create array for all days in month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::create($start->year, $start->month, $day);
            $label = $currentDate->format('M d');

            // Find data for this specific day
            $dayData = $data->firstWhere('day', $day);

            $labels[] = $label;
            $amounts[] = $dayData ? (float) $dayData->total_amount : 0;
        }

        return [
            'labels' => $labels,
            'data' => $amounts,
            'title' => $title
        ];
    }
}