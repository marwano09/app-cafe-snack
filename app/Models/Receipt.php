<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','type','total'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
