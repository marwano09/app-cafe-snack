<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',        // e.g. "Coca Cola 33cl"
        'sku',         // optional code
        'unit',        // "u", "bottle", "L", "kg"...
        'min_qty',     // minimum safe quantity
        'current_qty', // on hand
    ];

    // Helpers
    public function isLow(): bool
    {
        return (float)$this->current_qty <= (float)$this->min_qty;
    }

    // Relations
    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // If you use recipes (optional)
    public function recipeLines()
    {
        return $this->hasMany(MenuItemIngredient::class);
    }
}
