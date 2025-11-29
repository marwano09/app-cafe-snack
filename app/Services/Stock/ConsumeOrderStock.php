<?php

namespace App\Services\Stock;

use App\Models\Order;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class ConsumeOrderStock
{
    /**
     * Consume stock for an order one time only.
     */
    public function handle(Order $order): void
    {
        if ($order->stock_consumed_at) {
            return; // already done
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $oi) {
                $item = $oi->menuItem;
                if (!$item) continue;

                foreach ($item->recipe as $bom) {
                    $stock = $bom->stock;            // StockItem
                    if (!$stock) continue;

                    $need = (float) $bom->qty * (float) $oi->quantity; // e.g. 0.25L × 2
                    $before = $stock->available_qty;
                    $stock->available_qty = max(0, $before - $need);
                    $stock->save();

                    StockMovement::create([
                        'stock_item_id' => $stock->id,
                        'type'          => 'CONSUME',
                        'qty_change'    => -$need,
                        'note'          => 'Order #'.$order->id.' (auto consumption)',
                        'cost_total'    => null,
                        'user_id'       => optional(auth()->user())->id,
                    ]);
                }
            }

            $order->forceFill(['stock_consumed_at' => now()])->save();
        });
    }
}
