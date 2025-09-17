<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'table_number',
        'notes',
        'total',
        'user_id',
    ];

    /**
     * The waiter (user) who created the order.
     */
    public function waiter()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Order items relation.
     */
    public function items()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }
}
