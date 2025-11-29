@extends('layouts.app')
@section('title','الطلبات')

@section('content')
{{-- Header / Sort --}}
<div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
  <a href="{{ route('orders.create') }}"
     class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-sm transition">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 6v12m6-6H6"/>
    </svg>
    طلب جديد
  </a>

  <div class="inline-flex items-center gap-2 text-sm">
    <span class="opacity-70">ترتيب حسب التاريخ:</span>
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 p-1 bg-white dark:bg-neutral-900">
      <a href="{{ route('orders.index', ['sort'=>'desc']) }}"
         class="px-3 py-1.5 rounded-lg inline-block {{ ($sort??'desc')==='desc' ? 'bg-neutral-100 dark:bg-neutral-800 font-medium' : 'hover:bg-neutral-50 dark:hover:bg-neutral-800/60' }}">
        الأحدث
      </a>
      <a href="{{ route('orders.index', ['sort'=>'asc']) }}"
         class="px-3 py-1.5 rounded-lg inline-block {{ ($sort??'desc')==='asc' ? 'bg-neutral-100 dark:bg-neutral-800 font-medium' : 'hover:bg-neutral-50 dark:hover:bg-neutral-800/60' }}">
        الأقدم
      </a>
    </div>
  </div>
</div>

{{-- Table Card --}}
<div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 backdrop-blur p-0 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-neutral-50/80 dark:bg-neutral-800/80 sticky top-0 z-10">
        <tr class="text-neutral-700 dark:text-neutral-200">
          <th class="p-3 text-right">#</th>
          <th class="p-3">النادل</th>
          <th class="p-3">الحالة</th>
          <th class="p-3">المجموع</th>
          <th class="p-3">التاريخ</th>
          <th class="p-3">إجراءات</th>
        </tr>
      </thead>
      <tbody class="text-neutral-800 dark:text-neutral-100">
        @php
          $labels = [
            'PENDING'   => 'جديدة',
            'PREPARING' => 'قيد التحضير',
            'READY'     => 'جاهزة',
            'CANCELLED' => 'ملغاة',
          ];
        @endphp

        @foreach($orders as $o)
          @php
            $statusKey = (string) $o->status;
            $badgeClass = 'border-neutral-300 text-neutral-700 dark:border-neutral-700 dark:text-neutral-300';
            if ($statusKey === 'PREPARING') $badgeClass = 'border-amber-300 text-amber-700 dark:border-amber-700 dark:text-amber-300';
            elseif ($statusKey === 'READY') $badgeClass = 'border-emerald-300 text-emerald-700 dark:border-emerald-700 dark:text-emerald-300';
            elseif ($statusKey === 'CANCELLED') $badgeClass = 'border-rose-300 text-rose-700 dark:border-rose-700 dark:text-rose-300';
          @endphp
          <tr class="border-t border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50/60 dark:hover:bg-neutral-800/60 transition">
            <td class="p-3 tabular-nums">{{ $o->id }}</td>
            <td class="p-3">{{ optional($o->waiter)->name ?? '—' }}</td>
            <td class="p-3">
              <span class="inline-flex items-center gap-1 text-[11px] rounded-full px-2 py-0.5 border {{ $badgeClass }}">
                {{ $labels[$statusKey] ?? $statusKey }}
              </span>
            </td>
            <td class="p-3 tabular-nums">DH {{ number_format((float)$o->total,2) }}</td>
            <td class="p-3">{{ $o->created_at->format('H:i Y-m-d') }}</td>
            <td class="p-3">
              <div class="flex flex-wrap items-center gap-2">
                <a class="inline-flex items-center gap-1.5 text-sky-600 hover:underline dark:text-sky-400"
                   href="{{ route('orders.show',$o) }}">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Zm11 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                  </svg>
                  عرض
                </a>

                @can('update', $o)
                  <a class="inline-flex items-center gap-1.5 text-amber-600 hover:underline dark:text-amber-400"
                     href="{{ route('orders.edit',$o) }}">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5Z"/>
                    </svg>
                    تعديل
                  </a>
                @endcan

                <form method="POST" action="{{ route('orders.destroy',$o) }}"
                      onsubmit="return confirm('حذف هذا الطلب نهائياً؟')">
                  @csrf @method('DELETE')
                  <button class="inline-flex items-center gap-1.5 text-rose-600 hover:underline">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 7h16M6 7v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
                    </svg>
                    حذف
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach

        @if($orders->isEmpty())
          <tr>
            <td colspan="6" class="p-6 text-center text-neutral-500 dark:text-neutral-400">
              لا توجد طلبات.
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

  {{-- Footer: pagination --}}
  <div class="p-3 border-t border-neutral-100 dark:border-neutral-800">
    {{ $orders->links() }}
  </div>
</div>
@endsection
