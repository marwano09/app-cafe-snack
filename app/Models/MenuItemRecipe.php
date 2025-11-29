<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemRecipe extends Model
{
    protected $fillable = ['menu_item_id','stock_id','qty_per_unit'];

    public function menuItem() { return $this->belongsTo(MenuItem::class); }
    public function stock()    { return $this->belongsTo(Stock::class); }
}
