<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    public function create()
    {
        $items = StockItem::orderBy('name')->get();
        return view('stock.adjustments.create', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'stock_item_id' => ['required','exists:stock_items,id'],
            'qty_change'    => ['required','numeric','not_in:0'],
            'reason'        => ['required','string','max:500'],
        ]);

        $item = StockItem::findOrFail($data['stock_item_id']);

        // Adjust (can be + or -)
        $item->update([
            'current_qty' => (float)$item->current_qty + (float)$data['qty_change'],
        ]);

        StockMovement::create([
            'stock_item_id' => $item->id,
            'type'          => 'ADJUST',
            'qty_change'    => (float)$data['qty_change'],
            'note'          => $data['reason'],
            'user_id'       => auth()->id(),
        ]);

        return redirect()->route('stock.items.index')->with('ok', '✏️ تم تسجيل التسوية.');
    }
}
