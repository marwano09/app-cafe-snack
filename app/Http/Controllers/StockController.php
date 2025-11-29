<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $q = Stock::query()->orderBy('name');
        if ($s = $request->get('q')) {
            $q->where(function($qq) use ($s) {
                $qq->where('name','like',"%$s%")->orWhere('sku','like',"%$s%");
            });
        }
        $stocks = $q->paginate(20);
        return view('stock.index', compact('stocks'));
    }

    public function adjust(Request $request, Stock $stock)
    {
        $data = $request->validate([
            'type'   => 'required|in:IN,OUT,ADJUST',
            'qty'    => 'required|numeric|min:0.001',
            'reason' => 'nullable|string|max:200'
        ]);

        $qty = (float)$data['qty'];
        if ($data['type'] === 'IN')       $stock->qty_on_hand += $qty;
        elseif ($data['type'] === 'OUT')  $stock->qty_on_hand = max(0, $stock->qty_on_hand - $qty);
        else                               $stock->qty_on_hand = $qty;

        $stock->save();

        StockMovement::create([
            'stock_id'=>$stock->id,
            'type'=>$data['type'],
            'qty'=>$qty,
            'reason'=>$data['reason'] ?? 'Manual',
            'user_id'=>auth()->id(),
        ]);

        return back()->with('ok','تم تحديث المخزون.');
    }
}
