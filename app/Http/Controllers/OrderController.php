<?php

namespace App\Http\Controllers;
use App\Services\Stock\ConsumeOrderStock;
use App\Models\Ingredient;
use App\Models\IngredientMovement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Notifications\OrderStatusConfirmedNotification;
use App\Services\StockService;

class OrderController extends Controller
{
    public function create()
    {
        $cats = Category::with(['items' => function ($q) {
                $q->where('is_available', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('cats'));
    }

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
                'price'        => (float) $menu->price,
            ]);
        }

        $order->update(['total' => $total]);

        return redirect()->route('orders.index')->with('ok', 'تم إنشاء الطلب بنجاح ✅');
    }

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

    public function show(Order $order)
    {
        $this->authorizeView($order);

        $order->load('waiter','items.menuItem');

        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $this->authorizeUpdate($order);

        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order, StockService $stock)
    {
        $this->authorizeUpdate($order);

        $data = $request->validate([
            'status'       => ['required', Rule::in(['PENDING','PREPARING','READY','CANCELLED'])],
            'table_number' => ['nullable','integer','min:1'],
            'notes'        => ['nullable','string','max:1000'],
        ]);

        $oldStatus = $order->status;
        $order->update($data);

        $confirmationStates = ['PREPARING','READY'];
        if ($oldStatus !== $order->status && in_array($order->status, $confirmationStates, true)) {
            $managers = User::whereHas('roles', function($q){ $q->where('name','manager'); })->get();

            foreach ($managers as $manager) {
                $manager->notify(new OrderStatusConfirmedNotification(
                    orderId: $order->id,
                    tableNumber: $order->table_number,
                    total: (float) ($order->total ?? 0),
                    newStatus: $order->status,
                    changedByUserId: auth()->id() ?? 0
                ));
            }
        }

        if ($oldStatus !== 'PREPARING' && $order->status === 'PREPARING' && !$order->stock_consumed) {
            $stock->consumeForOrder($order, auth()->id());
        }

        if ($order->status === 'CANCELLED' && $order->stock_consumed) {
            $stock->restockForOrder($order, auth()->id());
        }

        return redirect()->route('orders.show', $order)->with('ok', 'تم تحديث الطلب ✅');
    }

    public function destroy(Order $order)
    {
        $this->authorizeDelete($order);

        $order->items()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('ok', 'تم حذف الطلب 🗑️');
    }

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