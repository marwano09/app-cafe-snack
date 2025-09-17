<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // ===== Cards (today) =====
        $ordersToday = (int) Order::whereDate('created_at', $today)->count();
        $salesToday  = (float) Order::whereDate('created_at', $today)
            ->where('status', '!=', 'CANCELLED')->sum('total');
        $cancelled   = (int) Order::whereDate('created_at', $today)
            ->where('status', 'CANCELLED')->count();
        $avgTicket   = $ordersToday > 0 ? round($salesToday / $ordersToday, 2) : 0.0;

        $cards = [
            'orders'     => $ordersToday,
            'sales'      => $salesToday,
            'cancelled'  => $cancelled,
            'avg_ticket' => $avgTicket,
        ];

        // ===== Sales chart (all days) =====
        $series = Order::selectRaw('DATE(created_at) as d,
                 SUM(CASE WHEN status != "CANCELLED" THEN total ELSE 0 END) as s')
            ->groupBy('d')->orderBy('d')->get();
        $chartLabels = $series->pluck('d')->map(fn ($d) => (string) $d)->toArray();
        $chartData   = $series->pluck('s')->map(fn ($v) => (float) $v)->toArray();

        // ===== Waiter performance =====
        $byWaiter = User::query()
            ->leftJoin('orders', 'orders.user_id', '=', 'users.id')
            ->select('users.id', 'users.name')
            ->selectRaw('COUNT(orders.id) as cnt')
            ->selectRaw('COALESCE(SUM(CASE WHEN orders.status != "CANCELLED" THEN orders.total ELSE 0 END), 0) as revenue')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('revenue')
            ->get();

        // ===== NEW: Orders by status (today) =====
        $byStatusToday = Order::whereDate('created_at', $today)
            ->select('status', DB::raw('COUNT(*) as c'))
            ->groupBy('status')->pluck('c','status')->toArray();
        // Ensure all keys exist
        foreach (['PENDING','PREPARING','READY','CANCELLED'] as $k) {
            $byStatusToday[$k] = (int)($byStatusToday[$k] ?? 0);
        }

        // ===== NEW: latest 8 orders (any day) =====
        $latestOrders = Order::with('waiter')->latest()->take(8)->get();

        return view('manager.index', compact(
            'cards','chartLabels','chartData','byWaiter',
            'byStatusToday','latestOrders'
        ));
    }
}
