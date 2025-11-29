<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image_path',
        'preparation_area',   // 'kitchen' | 'bar'
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }

    // <img src="{{ $category->image_url }}">
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::disk('public')->url($this->image_path);
        }
        // fallback placeholder in /public/images
        return asset('images/placeholder-category.png');
    }
}
