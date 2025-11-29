<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name','unit','stock_qty','reorder_level'];

    public function movements() {
        return $this->hasMany(IngredientMovement::class);
    }

    public function menuItems() {
        return $this->belongsToMany(MenuItem::class, 'menu_item_ingredients')
            ->withPivot('qty_per_unit');
    }

    public function inStock(float|int $needed): bool {
        return $this->stock_qty >= $needed;
    }
}
