<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockPurchaseController extends Controller
{
    public function create()
    {
        $items = StockItem::orderBy('name')->get();
        return view('stock.purchases.create', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'stock_item_id' => ['required','exists:stock_items,id'],
            'qty'           => ['required','numeric','gt:0'],
            'cost_total'    => ['nullable','numeric','gte:0'],
            'note'          => ['nullable','string','max:500'],
        ]);

        $item = StockItem::findOrFail($data['stock_item_id']);

        // Increase current quantity
        $item->increment('current_qty', (float)$data['qty']);

        // Log movement
        StockMovement::create([
            'stock_item_id' => $item->id,
            'type'          => 'PURCHASE',
            'qty_change'    => (float)$data['qty'],
            'note'          => $data['note'] ?? null,
            'cost_total'    => $data['cost_total'] ?? null,
            'user_id'       => auth()->id(),
        ]);

        return redirect()->route('stock.items.index')->with('ok', '✅ تم تسجيل التوريد.');
    }
}
