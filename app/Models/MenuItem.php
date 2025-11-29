<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'is_available',
        'category_id',
        'image_path', // stored relative to storage/app/public
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price'        => 'decimal:2',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients()
    {
        return $this->hasMany(MenuItemIngredient::class, 'menu_item_id');
    }

    // Accessor for image URL: {{ $item->image_url }}
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::disk('public')->url($this->image_path);
        }

        // fallback image (make sure you have public/images/placeholder-menu.png)
        return asset('images/placeholder-menu.png');
    }
}
