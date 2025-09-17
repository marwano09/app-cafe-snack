@extends('layouts.app')
@section('title',"طلب #{$order->id}")

@section('content')
<div class="flex items-center justify-between mb-4">
  <h2 class="text-lg font-semibold">تفاصيل الطلب #{{ $order->id }}</h2>
  <div class="flex gap-3">
    <a class="text-sm px-3 py-1 rounded bg-neutral-200 dark:bg-neutral-800" href="{{ route('orders.index') }}">الرجوع</a>
    <a class="text-sm px-3 py-1 rounded bg-amber-600 text-white" href="{{ route('orders.edit',$order) }}">تعديل</a>
  </div>
</div>

<div class="grid gap-4 md:grid-cols-2">
  <div class="rounded-xl border border-neutral-200/60 dark:border-neutral-800/80 p-4">
    <div class="mb-2">النادل: <b>{{ optional($order->waiter)->name ?? '—' }}</b></div>
    <div class="mb-2">الحالة: <b>{{ $order->status }}</b></div>
    <div class="mb-2">رقم الطاولة: <b>{{ $order->table_number ?? '—' }}</b></div>
    <div class="mb-2">ملاحظات: <b>{{ $order->notes ?? '—' }}</b></div>
    <div>المجموع: <b>DH {{ number_format((float)$order->total,2) }}</b></div>
  </div>

  <div class="rounded-xl border border-neutral-200/60 dark:border-neutral-800/80 p-4 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-neutral-50 dark:bg-neutral-800">
        <tr><th class="p-2 text-right">الصنف</th><th class="p-2">السعر</th><th class="p-2">الكمية</th><th class="p-2">الإجمالي</th></tr>
      </thead>
      <tbody>
        @foreach($order->items as $it)
          <tr class="border-t">
            <td class="p-2">{{ optional($it->menuItem)->name ?? '—' }}</td>
            <td class="p-2">DH {{ number_format((float)$it->price,2) }}</td>
            <td class="p-2">{{ $it->quantity }}</td>
            <td class="p-2">DH {{ number_format((float)$it->price * $it->quantity,2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
