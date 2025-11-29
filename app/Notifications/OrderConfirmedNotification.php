<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class OrderConfirmedNotification extends Notification
{
    public function __construct(
        public int $orderId,
        public ?int $tableNumber,
        public float $total,
        public string $newStatus,
        public int $changedByUserId
    ) {}

    public function via($notifiable): array
    {
        // database only; add 'mail' later if you need email
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'order_id'   => $this->orderId,
            'table'      => $this->tableNumber,
            'total'      => $this->total,
            'status'     => $this->newStatus,
            'changed_by' => $this->changedByUserId,
            'message'    => "تم تغيير حالة الطلب #{$this->orderId} إلى ".$this->arabicStatus($this->newStatus),
            'url'        => route('orders.show', $this->orderId),
        ];
    }

    private function arabicStatus(string $s): string
    {
        return match ($s) {
            'PENDING'    => 'قيد الانتظار',
            'PREPARING'  => 'قيد التحضير',
            'READY'      => 'جاهز',
            'CANCELLED'  => 'ملغي',
            default      => $s,
        };
    }
}
