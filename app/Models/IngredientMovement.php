<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientMovement extends Model
{
    protected $fillable = ['ingredient_id','type','quantity','reason','user_id'];

    public function ingredient() {
        return $this->belongsTo(Ingredient::class);
    }
}
