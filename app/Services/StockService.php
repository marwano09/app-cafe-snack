<?php

namespace App\Services;

use App\Models\Order;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function consumeForOrder(Order $order, int $userId = null): void
    {
        DB::transaction(function () use ($order, $userId) {
            foreach ($order->items as $oi) {
                $menu = $oi->menuItem;
                if (!$menu) continue;

                foreach ($menu->recipe as $bom) {
                    $stock = $bom->stock()->lockForUpdate()->first();
                    if (!$stock) continue;

                    $qty = (float)$bom->qty_per_unit * (int)$oi->quantity;

                    $stock->qty_on_hand = max(0, $stock->qty_on_hand - $qty);
                    $stock->save();

                    StockMovement::create([
                        'stock_id' => $stock->id,
                        'type'     => 'OUT',
                        'qty'      => $qty,
                        'reason'   => 'Order #'.$order->id,
                        'ref_type' => get_class($order),
                        'ref_id'   => $order->id,
                        'user_id'  => $userId,
                    ]);
                }
            }

            $order->update(['stock_consumed' => true]);
        });
    }

    public function restockForOrder(Order $order, int $userId = null): void
    {
        if (!$order->stock_consumed) return;

        DB::transaction(function () use ($order, $userId) {
            foreach ($order->items as $oi) {
                $menu = $oi->menuItem;
                if (!$menu) continue;

                foreach ($menu->recipe as $bom) {
                    $stock = $bom->stock()->lockForUpdate()->first();
                    if (!$stock) continue;

                    $qty = (float)$bom->qty_per_unit * (int)$oi->quantity;

                    $stock->qty_on_hand += $qty;
                    $stock->save();

                    StockMovement::create([
                        'stock_id' => $stock->id,
                        'type'     => 'IN',
                        'qty'      => $qty,
                        'reason'   => 'Cancel Order #'.$order->id,
                        'ref_type' => get_class($order),
                        'ref_id'   => $order->id,
                        'user_id'  => $userId,
                    ]);
                }
            }

            $order->update(['stock_consumed' => false]);
        });
    }
}
