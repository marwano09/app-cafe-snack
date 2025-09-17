@extends('layouts.app')
@section('title', $area === 'bar' ? 'طلبات البار' : 'طلبات المطبخ')

@section('content')
<div class="mb-4 flex items-center justify-between">
  <h1 class="text-xl font-bold">
    {{ $area === 'bar' ? 'طلبات البار' : 'طلبات المطبخ' }}
  </h1>

  @role('manager')
  <form method="GET" class="flex items-center gap-2">
    <select name="area" class="rounded-xl border px-3 py-2">
      <option value="kitchen" @selected($area==='kitchen')>المطبخ</option>
      <option value="bar"     @selected($area==='bar')>البار</option>
    </select>
    <button class="rounded-xl border px-3 py-2">عرض</button>
  </form>
  @endrole
</div>

<p class="text-sm opacity-70 mb-4">يمكنك تغيير الحالة وطباعة تذكرة التحضير</p>

<div class="grid gap-4 lg:grid-cols-2">
  @php $found = false; @endphp
  @foreach($orders as $order)
    @php
      // Only the items for the current area
      $areaItems = $order->items->filter(
        fn($it) => optional(optional($it->menuItem)->category)->preparation_area === $area
      );
    @endphp

    @if($areaItems->isEmpty())
      @continue
    @endif
    @php $found = true; @endphp

    <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 p-4">
      <div class="flex items-center justify-between mb-2">
        <div class="font-semibold">
          طلب #{{ $order->id }}
        </div>
        <div class="text-xs opacity-70">
          {{ $order->created_at->format('d/m H:i') }}
        </div>
      </div>

      <div class="text-sm opacity-80 mb-2">
        النادل: <span class="font-medium">{{ optional($order->waiter)->name ?? '—' }}</span>
      </div>

      <ul class="text-sm list-disc ms-4 mb-3">
        @foreach($areaItems as $it)
          <li>
            {{ optional($it->menuItem)->name }} —
            <span class="font-medium">{{ $it->quantity }}</span>
          </li>
        @endforeach
      </ul>

      <div class="flex items-center gap-2">
        <form action="{{ route('orders.status',$order) }}" method="post" class="flex gap-2">
          @csrf @method('PATCH')
          <select name="status" class="rounded-lg px-3 py-2 bg-neutral-100 dark:bg-neutral-800">
            @foreach(['PENDING'=>'قيد الانتظار','PREPARING'=>'قيد التحضير','READY'=>'جاهز','CANCELLED'=>'ملغى'] as $k=>$v)
              <option value="{{ $k }}" @selected($order->status===$k)>{{ $v }}</option>
            @endforeach
          </select>
          <button class="rounded-lg px-4 bg-emerald-600 hover:bg-emerald-700 text-white">حفظ</button>
        </form>

        {{-- Print preparation ticket (existing route) --}}
        <a target="_blank"
           href="{{ route('receipts.kitchen',$order) }}"
           class="rounded-lg px-4 border hover:bg-neutral-100 dark:hover:bg-neutral-800">
          طباعة تذكرة التحضير
        </a>
      </div>
    </div>
  @endforeach

  @if(!$found)
    <div class="opacity-60">لا توجد طلبات لهذا القسم حالياً.</div>
  @endif
</div>
@endsection
