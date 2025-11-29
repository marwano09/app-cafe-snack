<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_item_id',
        'type',        // PURCHASE | CONSUME | ADJUST
        'qty_change',  // + or -
        'note',
        'cost_total',  // optional for purchases
        'user_id',
    ];

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
