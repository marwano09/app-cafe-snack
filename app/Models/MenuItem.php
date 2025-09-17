<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter; // 👈 add this

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'is_available',
        'category_id',
        'image_path', // store relative path
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Accessor: get full image URL (or fallback).
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            /** @var FilesystemAdapter $disk */
            $disk = Storage::disk('public');   // force IDE to see correct type
            return $disk->url($this->image_path);
        }

        // fallback if no image exists
        return asset('images/placeholder-menu.png');
    }
}
