<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Year summary (monthly totals)
     */
    public function year(Request $request, ?int $year = null)
    {
        $year = $year ?: (int) now()->year;

        $q = Order::query();
        if (auth()->user()->hasRole('waiter')) {
            $q->where('user_id', auth()->id());
        }

        $rows = $q->selectRaw('YEAR(created_at) as y, MONTH(created_at) as m')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(CASE WHEN status != "CANCELLED" THEN total ELSE 0 END) as revenue')
            ->whereYear('created_at', $year)
            ->groupBy('y', 'm')
            ->orderBy('m')
            ->get();

        $byMonth = [];
        foreach ($rows as $r) {
            $byMonth[(int)$r->m] = [
                'orders'  => (int)$r->orders_count,
                'revenue' => (float)$r->revenue,
            ];
        }

        return view('history.year', compact('year', 'byMonth'));
    }

    /**
     * Month calendar (grid of days)
     */
    public function month(Request $request, ?int $year = null, ?int $month = null)
    {
        $today = now();
        $year  = $year ?: (int) $today->year;
        $month = $month ?: (int) $today->month;

        $firstDay   = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $daysInMon  = $firstDay->daysInMonth;
        $startWeek  = (int)$firstDay->copy()->dayOfWeekIso; // 1..7
        $prevMonth  = $firstDay->copy()->subMonth();
        $nextMonth  = $firstDay->copy()->addMonth();

        $q = Order::query();
        if (auth()->user()->hasRole('waiter')) {
            $q->where('user_id', auth()->id());
        }

        $rows = $q->selectRaw('DATE(created_at) as d')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(CASE WHEN status != "CANCELLED" THEN total ELSE 0 END) as revenue')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $byDay = [];
        foreach ($rows as $r) {
            $byDay[(string)$r->d] = [
                'orders'  => (int)$r->orders_count,
                'revenue' => (float)$r->revenue,
            ];
        }

        // ✅ Add dailyStats array for indexed access by day number
        $dailyStats = [];
        for ($d = 1; $d <= $daysInMon; $d++) {
            $date = Carbon::createFromDate($year, $month, $d)->toDateString();
            $row  = $byDay[$date] ?? ['orders' => 0, 'revenue' => 0];
            $dailyStats[$d] = $row;
        }

        return view('history.month', compact(
            'year', 'month', 'daysInMon', 'startWeek', 'prevMonth', 'nextMonth', 'byDay', 'dailyStats'
        ));
    }

    /**
     * Single day detail (orders list + totals)
     */
    public function day(Request $request, int $year, int $month, int $day)
    {
        $date = Carbon::createFromDate($year, $month, $day)->toDateString();

        $q = Order::with(['waiter', 'items.menuItem'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc');

        if (auth()->user()->hasRole('waiter')) {
            $q->where('user_id', auth()->id());
        }

        $orders = $q->get();

        $totals = [
            'orders'  => $orders->count(),
            'revenue' => (float) $orders->where('status', '!=', 'CANCELLED')->sum('total'),
            'cancel'  => (int) $orders->where('status', 'CANCELLED')->count(),
            'avg'     => $orders->count()
                ? round($orders->where('status', '!=', 'CANCELLED')->sum('total') / $orders->count(), 2)
                : 0,
        ];

        $hourly = Order::selectRaw('HOUR(created_at) as h')
            ->selectRaw('SUM(CASE WHEN status != "CANCELLED" THEN total ELSE 0 END) as s')
            ->whereDate('created_at', $date)
            ->groupBy('h')
            ->orderBy('h')
            ->get();

        $chartLabels = $hourly->pluck('h')->map(fn($h) => sprintf('%02d:00', $h))->toArray();
        $chartData   = $hourly->pluck('s')->map(fn($v) => (float)$v)->toArray();

        return view('history.day', compact(
            'date', 'orders', 'totals', 'chartLabels', 'chartData', 'year', 'month', 'day'
        ));
    }
}