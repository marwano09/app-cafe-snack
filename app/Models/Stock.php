<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'name','sku','unit','qty_on_hand','min_qty','location','is_active'
    ];

    public function movements() { return $this->hasMany(StockMovement::class); }

    public function isLow(): bool { return $this->qty_on_hand <= $this->min_qty; }
}
