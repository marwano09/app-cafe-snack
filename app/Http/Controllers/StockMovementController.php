<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $items = StockItem::orderBy('name')->get();

        $movements = StockMovement::with(['stockItem','user'])
            ->when($request->filled('stock_item_id'), fn($q) =>
                $q->where('stock_item_id', $request->integer('stock_item_id'))
            )
            ->when($request->filled('type'), fn($q) =>
                $q->where('type', $request->string('type'))
            )
            ->when($request->filled('from'), fn($q) =>
                $q->whereDate('created_at', '>=', $request->date('from'))
            )
            ->when($request->filled('to'), fn($q) =>
                $q->whereDate('created_at', '<=', $request->date('to'))
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('stock.movements.index', compact('movements','items'));
    }
}
