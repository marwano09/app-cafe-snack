<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'preparation_area',   // <-- add this
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }
}
