<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index(Request $request)
    {
        // Decide area from query or role (manager can switch)
        if ($request->filled('area')) {
            $area = strtolower($request->string('area')->value());
            if (!in_array($area, ['bar','kitchen'], true)) {
                $area = 'kitchen';
            }
        } else {
            $area = auth()->user()->hasRole('bar') ? 'bar' : 'kitchen';
        }

        // Active orders with needed relations
        $orders = Order::with(['waiter','items.menuItem.category'])
            ->whereIn('status', ['PENDING','PREPARING'])
            // Show only items that belong to this prep area via the category
            ->whereHas('items.menuItem.category', function ($q) use ($area) {
                $q->where('preparation_area', $area);   // <— important change
            })
            ->latest()
            ->get();

        return view('kitchen.index', compact('orders','area'));
    }

    public function status(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:PENDING,PREPARING,READY,CANCELLED',
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('ok', 'تم حفظ الحالة.');
    }
}
