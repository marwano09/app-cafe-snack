<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index(Request $request)
    {
        // Resolve which area to show:
        // - bar role -> 'bar'
        // - kitchen role -> 'kitchen'
        // - manager -> from query ?area=bar|kitchen (default kitchen)
        if (auth()->user()->hasRole('bar')) {
            $area = 'bar';
        } elseif (auth()->user()->hasRole('kitchen')) {
            $area = 'kitchen';
        } else { // manager
            $area = $request->query('area', 'kitchen');
            if (!in_array($area, ['bar','kitchen'], true)) {
                $area = 'kitchen';
            }
        }

        // Fetch orders that have at least one item belonging to categories of that area
        $orders = Order::with(['waiter','items.menuItem.category'])
            ->whereIn('status', ['PENDING','PREPARING','READY'])
            ->whereHas('items.menuItem.category', function($q) use ($area) {
                $q->where('preparation_area', $area);
            })
            ->latest()
            ->get();

        return view('kitchen.index', compact('orders','area'));
    }

    public function status(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required','in:PENDING,PREPARING,READY,CANCELLED'],
        ]);
        $order->update(['status' => $validated['status']]);

        return back()->with('ok', 'تم تحديث حالة الطلب');
    }
}
