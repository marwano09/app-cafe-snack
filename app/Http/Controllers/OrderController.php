<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Category;                 // ← NEW: load categories for tabs
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Waiter/Manager: show create screen (tabbed by categories).
     */
    public function create()
    {
        // Categories with only available items, nicely ordered
        $cats = Category::with(['items' => function ($q) {
                $q->where('is_available', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('cats'));
    }

    /**
     * Persist new order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number'         => ['nullable','integer','min:1'],
            'notes'                => ['nullable','string','max:1000'],
            'items'                => ['required','array','min:1'],
            'items.*.menu_item_id' => ['required','exists:menu_items,id'],
            'items.*.quantity'     => ['required','integer','min:1'],
        ]);

        $order = Order::create([
            'status'       => 'PENDING',
            'table_number' => $validated['table_number'] ?? null,
            'notes'        => $validated['notes'] ?? null,
            'total'        => 0,
            'user_id'      => auth()->id(),
        ]);

        $total = 0.0;

        foreach ($validated['items'] as $row) {
            $menu = MenuItem::findOrFail($row['menu_item_id']);

            $lineTotal = (float) $menu->price * (int) $row['quantity'];
            $total    += $lineTotal;

            OrderItem::create([
                'order_id'     => $order->id,
                'menu_item_id' => $menu->id,
                'quantity'     => (int) $row['quantity'],
                'price'        => (float) $menu->price, // unit price snapshot
            ]);
        }

        $order->update(['total' => $total]);

        return redirect()->route('orders.index')->with('ok', 'تم إنشاء الطلب بنجاح ✅');
    }

    /**
     * List orders (manager = all, waiter = own), sort by date asc/desc.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'desc');
        $sort = in_array($sort, ['asc','desc'], true) ? $sort : 'desc';

        $q = Order::with(['waiter','items.menuItem'])->orderBy('created_at', $sort);

        if (auth()->user()->hasRole('waiter')) {
            $q->where('user_id', auth()->id());
        }

        $orders = $q->paginate(12)->withQueryString();

        return view('orders.index', compact('orders','sort'));
    }

    /**
     * Show one order.
     */
    public function show(Order $order)
    {
        $this->authorizeView($order);

        $order->load('waiter','items.menuItem');

        return view('orders.show', compact('order'));
    }

    /**
     * Edit order (status/table/notes only).
     */
    public function edit(Order $order)
    {
        $this->authorizeUpdate($order);

        return view('orders.edit', compact('order'));
    }

    /**
     * Update order (status/table/notes).
     */
    public function update(Request $request, Order $order)
    {
        $this->authorizeUpdate($order);

        $data = $request->validate([
            'status'       => ['required', Rule::in(['PENDING','PREPARING','READY','CANCELLED'])],
            'table_number' => ['nullable','integer','min:1'],
            'notes'        => ['nullable','string','max:1000'],
        ]);

        $order->update($data);

        return redirect()->route('orders.show', $order)->with('ok', 'تم تحديث الطلب ✅');
    }

    /**
     * Delete order.
     */
    public function destroy(Order $order)
    {
        $this->authorizeDelete($order);

        $order->items()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('ok', 'تم حذف الطلب 🗑️');
    }

    // ---------------- Authorization helpers ----------------

    private function authorizeView(Order $order): void
    {
        if (auth()->user()->hasRole('manager')) return;
        if (auth()->user()->hasRole('waiter') && $order->user_id === auth()->id()) return;
        abort(403);
    }

    private function authorizeUpdate(Order $order): void
    {
        if (auth()->user()->hasRole('manager')) return;
        if (auth()->user()->hasRole('waiter') && $order->user_id === auth()->id() && $order->status === 'PENDING') return;
        abort(403);
    }

    private function authorizeDelete(Order $order): void
    {
        if (auth()->user()->hasRole('manager')) return;
        if (auth()->user()->hasRole('waiter') && $order->user_id === auth()->id() && $order->status === 'PENDING') return;
        abort(403);
    }
}
