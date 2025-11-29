<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockItemController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->query('q', ''));

        $items = StockItem::query()
            ->when($q, fn($qq) =>
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('sku', 'like', "%{$q}%")
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // Optional: today’s movements summary
        $todayMovements = StockMovement::with('stockItem','user')
            ->whereDate('created_at', now()->toDateString())
            ->latest()
            ->take(20)
            ->get();

        return view('stock.items.index', compact('items', 'todayMovements'));
    }

    public function create()
    {
        $item = new StockItem();
        return view('stock.items.create', compact('item'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required','string','max:255'],
            'sku'         => ['nullable','string','max:255'],
            'unit'        => ['nullable','string','max:32'],
            'min_qty'     => ['nullable','numeric'],
            'current_qty' => ['nullable','numeric'],
        ]);

        $item = StockItem::create([
            'name'        => $data['name'],
            'sku'         => $data['sku'] ?? null,
            'unit'        => $data['unit'] ?? 'u',
            'min_qty'     => (float)($data['min_qty'] ?? 0),
            'current_qty' => (float)($data['current_qty'] ?? 0),
        ]);

        return redirect()->route('stock.items.index')->with('ok', '✅ تم إنشاء المادة.');
    }

    public function edit(StockItem $item)
    {
        return view('stock.items.edit', compact('item'));
    }

    public function update(Request $request, StockItem $item)
    {
        $data = $request->validate([
            'name'        => ['required','string','max:255'],
            'sku'         => ['nullable','string','max:255'],
            'unit'        => ['nullable','string','max:32'],
            'min_qty'     => ['nullable','numeric'],
            'current_qty' => ['nullable','numeric'],
        ]);

        $item->update([
            'name'        => $data['name'],
            'sku'         => $data['sku'] ?? null,
            'unit'        => $data['unit'] ?? 'u',
            'min_qty'     => (float)($data['min_qty'] ?? 0),
            'current_qty' => (float)($data['current_qty'] ?? 0),
        ]);

        return redirect()->route('stock.items.index')->with('ok', '✅ تم تحديث المادة.');
    }

    public function destroy(StockItem $item)
    {
        // Optional: prevent delete if it has movements / recipe links
        // if ($item->movements()->exists()) { return back()->with('error','لا يمكن الحذف لوجود سجل حركات'); }

        $item->delete();
        return redirect()->route('stock.items.index')->with('ok', '🗑️ تم حذف المادة.');
    }
}
