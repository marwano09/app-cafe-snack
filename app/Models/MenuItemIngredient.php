<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemIngredient extends Model
{
    protected $fillable = ['menu_item_id','stock_item_id','qty'];

    public function stock()
    {
        return $this->belongsTo(StockItem::class, 'stock_item_id');
    }
}
