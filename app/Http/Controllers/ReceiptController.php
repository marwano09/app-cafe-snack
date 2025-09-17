<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    // فاتورة الزبون (مع الأسعار)
    public function customer(Order $order)
    {
        $order->load('items.menuItem','waiter');
        $pdf = Pdf::loadView('receipts.customer', compact('order'));
        return $pdf->download("receipt_{$order->id}_customer.pdf");
    }

    // تذكرة المطبخ/البار (بدون أسعار)
    public function kitchen(Order $order)
    {
        $order->load('items.menuItem','waiter');
        $pdf = Pdf::loadView('receipts.kitchen', compact('order'));
        return $pdf->download("ticket_{$order->id}_kitchen.pdf");
    }
}
