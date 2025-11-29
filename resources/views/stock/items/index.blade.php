@extends('layouts.app')
@section('title','المخزون')

@section('content')
<div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4 bg-white dark:bg-neutral-900 shadow-sm">
  <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-lg font-semibold tracking-tight">لوحة المخزون</h1>
      <p class="text-sm opacity-70">تتبّع المواد، الحدود الدنيا، والتنبيهات فورًا.</p>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('stock.items.create') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-black text-white px-4 py-2 hover:opacity-90 transition">
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        مادة جديدة
      </a>
      <a href="{{ route('stock.purchases.create') }}"
         class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 px-4 py-2 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13l-2 7h14M7 13l-2-8"/></svg>
        شراء/توريد
      </a>
      <a href="{{ route('stock.adjustments.create') }}"
         class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 px-4 py-2 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-7 7-4-4"/></svg>
        تسوية
      </a>
    </div>
  </div>

  <div class="mt-4">
    <form method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-center">
      <div class="relative flex-1 min-w-64">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث عن مادة…"
               class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 pr-9 focus:outline-none focus:ring-2 focus:ring-black/10 dark:focus:ring-white/10 transition" />
        <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 size-4 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14Z"/></svg>
      </div>
      <button class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 px-4 py-2 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h12M3 18h6"/></svg>
        تصفية
      </button>
      @if(request('q'))
        <a href="{{ url()->current() }}"
           class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs border border-transparent hover:border-neutral-300 dark:hover:border-neutral-700 transition">
          إعادة ضبط
        </a>
      @endif
    </form>
  </div>
</div>

<div class="mt-4 overflow-hidden rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="text-[11px] uppercase tracking-wider border-b border-neutral-200 dark:border-neutral-800 bg-neutral-50/60 dark:bg-neutral-900/60 backdrop-blur supports-[backdrop-filter]:backdrop-blur sticky top-0 z-10">
        <tr class="text-right">
          <th class="px-3 py-3 font-semibold">المادة</th>
          <th class="px-3 py-3 font-semibold">الوحدة</th>
          <th class="px-3 py-3 font-semibold">الحد الأدنى</th>
          <th class="px-3 py-3 font-semibold">المتاح</th>
          <th class="px-3 py-3 font-semibold">تنبيه</th>
          <th class="px-3 py-3 font-semibold">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $it)
          @php $isLow = $it->isLow(); @endphp
          <tr @class([
                'border-b border-neutral-100 dark:border-neutral-800 group transition',
                // تمييز الصف المنخفض المخزون بلطف
                'bg-rose-50/40 dark:bg-rose-950/20' => $isLow,
                'hover:bg-neutral-50 dark:hover:bg-neutral-800/60' => true,
              ])>
            <td class="px-3 py-3 align-middle">
              <div class="font-medium leading-5">{{ $it->name }}</div>
              @if($it->sku)
                <div class="text-[11px] opacity-60 mt-0.5">SKU: {{ $it->sku }}</div>
              @endif
            </td>
            <td class="px-3 py-3 align-middle">{{ $it->unit ?? '—' }}</td>
            <td class="px-3 py-3 align-middle">
              <span class="tabular-nums">{{ $it->min_qty ?? 0 }}</span>
            </td>
            <td class="px-3 py-3 align-middle">
              <span class="tabular-nums">{{ number_format($it->current_qty,2) }}</span>
            </td>
            <td class="px-3 py-3 align-middle">
              @if($isLow)
                <span class="inline-flex items-center gap-1 text-[10px] px-2 py-0.5 rounded-full border border-rose-500 text-rose-600 bg-rose-50 dark:bg-rose-950/40">
                  <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/></svg>
                  منخفض
                </span>
              @else
                <span class="inline-flex items-center gap-1 text-[10px] px-2 py-0.5 rounded-full border border-emerald-500 text-emerald-700 bg-emerald-50 dark:bg-emerald-900/30">
                  <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                  جيد
                </span>
              @endif
            </td>
            <td class="px-3 py-3 align-middle">
              <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('stock.items.edit',$it) }}"
                   class="inline-flex items-center gap-1 text-xs rounded-lg px-2.5 py-1.5 border border-neutral-300 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition"
                   aria-label="تعديل {{ $it->name }}">
                  <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 20h9M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"/></svg>
                  تعديل
                </a>
                <form method="POST" action="{{ route('stock.items.destroy',$it) }}"
                      onsubmit="return confirm('هل تريد حذف هذه المادة؟');">
                  @csrf @method('DELETE')
                  <button type="submit"
                          class="inline-flex items-center gap-1 text-xs rounded-lg px-2.5 py-1.5 border border-rose-300 text-rose-600 hover:bg-rose-50 dark:border-rose-800 dark:hover:bg-rose-900/30 transition"
                          aria-label="حذف {{ $it->name }}">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m1 0-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0v14m6-14v14"/></svg>
                    حذف
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-3 py-12 text-center">
              <div class="flex flex-col items-center gap-2 opacity-70">
                <svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M20 21V7a2 2 0 0 0-2-2h-3V3a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v2H6a2 2 0 0 0-2 2v14m16 0H4m16 0H4m3-10h10m-9 4h8"/></svg>
                <div class="font-medium">لا توجد مواد</div>
                <div class="text-xs">ابدأ بإضافة مادة جديدة من الزر أعلى اليمين.</div>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-4">
  {{ $items->links() }}
</div>

{{-- مختصر حركة اليوم --}}
@if(isset($todayMovements) && $todayMovements->count())
  <div class="mt-6 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4 bg-white dark:bg-neutral-900 shadow-sm">
    <div class="flex items-center justify-between mb-2">
      <div class="font-semibold">حركات اليوم</div>
      <span class="text-[11px] px-2 py-0.5 rounded-full border border-neutral-300 dark:border-neutral-700">
        {{ $todayMovements->count() }} عملية
      </span>
    </div>
    <ul class="text-sm space-y-1">
      @foreach($todayMovements as $mv)
        <li class="flex items-center gap-2">
          <span class="inline-flex items-center justify-center size-5 rounded-full border border-neutral-300 dark:border-neutral-700 text-[10px]">
            {{ $mv->type === 'in' ? '+' : '−' }}
          </span>
          <span class="font-medium">{{ optional($mv->stockItem)->name }}</span>
          <span class="opacity-70">— {{ $mv->type }}: {{ $mv->qty_change > 0 ? '+' : '' }}{{ $mv->qty_change }}</span>
          <span class="ms-auto opacity-60 text-xs">{{ $mv->created_at->format('H:i') }}</span>
        </li>
      @endforeach
    </ul>
  </div>
@endif
@endsection
