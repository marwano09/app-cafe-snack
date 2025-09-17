<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcast
{
    use SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return new Channel('orders'); // القناة اللي غادي يسمع لها المطبخ/البار
    }

    public function broadcastAs()
    {
        return 'order.status'; // اسم الحدث اللي كيسمع ليه JavaScript
    }
}
