{{-- resources/views/kitchen/index.blade.php --}}
@php
  // Area resolve
  $area = ($area ?? null) === 'bar'
      ? 'bar'
      : (auth()->user()->hasRole('bar') ? 'bar' : 'kitchen');

  $areaLabel = $area === 'bar' ? 'طلبات البار' : 'طلبات المطبخ';

  // Status map
  $statusMap = [
    'PENDING'   => ['label' => 'قيد الانتظار', 'color' => 'neutral', 'step' => 1],
    'PREPARING' => ['label' => 'قيد التحضير', 'color' => 'amber',   'step' => 2],
    'READY'     => ['label' => 'جاهز',        'color' => 'emerald', 'step' => 3],
    'CANCELLED' => ['label' => 'ملغى',        'color' => 'rose',    'step' => 0],
  ];

  // Quick counters (على مستوى هذه الصفحة فقط)
  $counters = ['total'=>0,'pending'=>0,'preparing'=>0,'ready'=>0,'cancelled'=>0];
  foreach ($orders as $o) {
    $areaItems = $o->items->filter(function ($it) use ($area) {
      $cat = optional(optional($it->menuItem)->category);
      return $cat && $cat->preparation_area === $area;
    });
    if ($areaItems->isEmpty()) continue;
    $counters['total']++;
    if ($o->status === 'PENDING')   $counters['pending']++;
    if ($o->status === 'PREPARING') $counters['preparing']++;
    if ($o->status === 'READY')     $counters['ready']++;
    if ($o->status === 'CANCELLED') $counters['cancelled']++;
  }

  // Active filters from query
  $filterStatus = request('status'); // null or one of keys
@endphp

@extends('layouts.app')
@section('title', $areaLabel)

@section('content')
  {{-- Header --}}
  <div class="mb-4 sm:mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div class="flex items-center gap-3">
        <h1 class="text-2xl font-extrabold tracking-tight">{{ $areaLabel }}</h1>
        <span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs
                     {{ $area==='bar'
                        ? 'border-amber-300 text-amber-700 dark:border-amber-700 dark:text-amber-300'
                        : 'border-emerald-300 text-emerald-700 dark:border-emerald-700 dark:text-emerald-300' }}">
          <span class="size-1.5 rounded-full {{ $area==='bar' ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
          {{ $area==='bar' ? 'البار' : 'المطبخ' }}
        </span>
      </div>

      <div class="flex items-center gap-2 text-xs">
        <span class="inline-flex items-center gap-1 rounded-lg border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-2.5 py-1">
          الإجمالي: <strong class="tabular-nums">{{ $counters['total'] }}</strong>
        </span>
        <span class="inline-flex items-center gap-1 rounded-lg border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-2.5 py-1">
          انتظار: <strong class="tabular-nums">{{ $counters['pending'] }}</strong>
        </span>
        <span class="inline-flex items-center gap-1 rounded-lg border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-2.5 py-1">
          تحضير: <strong class="tabular-nums">{{ $counters['preparing'] }}</strong>
        </span>
        <span class="inline-flex items-center gap-1 rounded-lg border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-2.5 py-1">
          جاهز: <strong class="tabular-nums">{{ $counters['ready'] }}</strong>
        </span>
      </div>
    </div>
    <p class="text-sm opacity-70 mt-1">بدّل الحالة بسرعة واطبع تذكرة التحضير أو فاتورة الزبون. يمكنك التصفية والبحث في النتائج مباشرة.</p>
  </div>

  {{-- Toolbar (sticky) --}}
  <div class="sticky top-2 z-10">
    <div class="rounded-2xl border border-neutral-200/70 dark:border-neutral-800/80 bg-white/90 dark:bg-neutral-900/90 backdrop-blur p-3 sm:p-4 shadow-sm">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
          {{-- Status filter --}}
          <form method="GET" class="flex flex-wrap items-center gap-2">
            <input type="hidden" name="area" value="{{ $area }}">
            <label class="text-xs opacity-70">الحالة</label>
            <select name="status"
                    class="rounded-xl border border-neutral-300/60 dark:border-neutral-700/70 bg-white dark:bg-neutral-900 px-3 py-2 text-sm">
              <option value="">الكل</option>
              @foreach($statusMap as $code => $meta)
                <option value="{{ $code }}" {{ $filterStatus===$code ? 'selected':'' }}>{{ $meta['label'] }}</option>
              @endforeach
            </select>
            <button class="rounded-xl border px-4 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">تطبيق</button>
            @if(request()->has('status'))
              <a href="{{ route('kitchen.index', ['area'=>$area]) }}"
                 class="rounded-xl border px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">إعادة ضبط</a>
            @endif
          </form>

          {{-- In-page search --}}
          <div class="relative">
            <input id="q" type="text" placeholder="ابحث بالنادل/الطاولة/المعرف..."
                   class="peer rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 ps-9 pe-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 w-64">
            <svg class="absolute start-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 peer-focus:text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="m21 20.3-4.7-4.7a7.5 7.5 0 1 0-1.4 1.4l4.7 4.7 1.4-1.4ZM4.5 10a5.5 5.5 0 1 1 11 0 5.5 5.5 0 0 1-11 0Z"/></svg>
          </div>
        </div>

        <div class="flex items-center gap-2">
          {{-- Auto refresh toggle --}}
          <label class="inline-flex items-center gap-2 text-xs select-none">
            <input id="autorefresh" type="checkbox" class="sr-only peer">
            <span class="w-10 h-5 rounded-full bg-neutral-300 dark:bg-neutral-700 relative transition peer-checked:bg-blue-500 after:content-[''] after:absolute after:top-0.5 after:start-0.5 after:w-4 after:h-4 after:bg-white after:rounded-full after:transition peer-checked:after:translate-x-5"></span>
            <span>تحديث تلقائي (10ث)</span>
          </label>

          @role('manager')
          {{-- Area switch (manager only) --}}
          <form method="GET" class="flex items-center gap-2">
            <label class="text-xs opacity-70">القسم</label>
            <select name="area"
                    class="rounded-xl border border-neutral-300/60 dark:border-neutral-700/70 bg-white dark:bg-neutral-900 px-3 py-2 text-sm">
              <option value="kitchen" @selected($area==='kitchen')>المطبخ</option>
              <option value="bar"     @selected($area==='bar')>البار</option>
            </select>
            <button class="rounded-xl border px-4 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">عرض</button>
          </form>
          @endrole
        </div>
      </div>
    </div>
  </div>

  {{-- Orders --}}
  @php $found = false; @endphp
  <div id="ordersGrid" class="grid gap-4 lg:grid-cols-2 mt-4">
    @foreach($orders as $order)
      @php
        // Keep only items that belong to this preparation area
        $areaItems = $order->items->filter(function ($it) use ($area) {
          $cat = optional(optional($it->menuItem)->category);
          return $cat && $cat->preparation_area === $area;
        });

        if ($areaItems->isEmpty()) continue;

        $found = true;
        $meta = $statusMap[$order->status] ?? ['label'=>$order->status,'color'=>'neutral','step'=>0];
        $color = $meta['color'];  // tailwind color name
        $step  = $meta['step'];   // 0..3
      @endphp

      {{-- Card --}}
      <div class="order-card rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/80 dark:bg-neutral-900/70 p-4 shadow-sm hover:shadow-md transition-shadow"
           data-status="{{ $order->status }}"
           data-text="{{ 'طلب #'.$order->id.' '.($order->waiter->name ?? '').' '.($order->table_number ?? '') }}">
        {{-- Top row: meta --}}
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div class="font-semibold">طلب #{{ $order->id }}</div>
            <span class="text-[10px] rounded-full px-2 py-0.5 border
              @switch($color)
                @case('neutral')  border-neutral-300 text-neutral-600 dark:border-neutral-700 dark:text-neutral-300 @break
                @case('amber')    border-amber-300 text-amber-700 dark:border-amber-700 dark:text-amber-300 @break
                @case('emerald')  border-emerald-300 text-emerald-700 dark:border-emerald-700 dark:text-emerald-300 @break
                @case('rose')     border-rose-300 text-rose-700 dark:border-rose-700 dark:text-rose-300 @break
              @endswitch">
              {{ $meta['label'] }}
            </span>
          </div>
          <div class="text-xs opacity-70">{{ $order->created_at->format('d/m H:i') }}</div>
        </div>

        {{-- waiter / table --}}
        <div class="text-sm opacity-80 mt-1">
          النادل: <span class="font-medium">{{ optional($order->waiter)->name ?? '—' }}</span>
          @if($order->table_number)
            <span class="opacity-60">— الطاولة:</span> {{ $order->table_number }}
          @endif
        </div>

        {{-- Items --}}
        <div class="mt-3">
          <ul class="text-sm divide-y divide-neutral-100 dark:divide-neutral-800 rounded-xl overflow-hidden border border-neutral-100 dark:border-neutral-800">
            @foreach($areaItems as $it)
              <li class="flex items-center justify-between gap-2 px-3 py-2 bg-neutral-50/40 dark:bg-neutral-800/40">
                <span class="truncate">{{ optional($it->menuItem)->name }}</span>
                <span class="font-semibold tabular-nums">× {{ $it->quantity }}</span>
              </li>
            @endforeach
          </ul>
        </div>

        {{-- Status timeline --}}
        <div class="mt-3">
          <div class="flex items-center gap-2 text-[10px]">
            @php $stages = ['PENDING'=>'انتظار','PREPARING'=>'تحضير','READY'=>'جاهز']; @endphp
            @foreach($stages as $code => $label)
              @php
                $active = ($statusMap[$code]['step'] ?? 0) <= $step && $order->status!=='CANCELLED';
              @endphp
              <span class="inline-flex items-center gap-1">
                <span class="size-1.5 rounded-full {{ $active ? 'bg-emerald-500' : 'bg-neutral-300 dark:bg-neutral-700' }}"></span>
                <span class="{{ $active ? 'text-emerald-700 dark:text-emerald-300' : 'opacity-60' }}">{{ $label }}</span>
              </span>
              @if(!$loop->last)
                <span class="grow h-px {{ $active ? 'bg-emerald-300 dark:bg-emerald-800' : 'bg-neutral-200 dark:bg-neutral-800' }}"></span>
              @endif
            @endforeach
          </div>
        </div>

        {{-- Actions --}}
        <div class="mt-4 flex flex-wrap items-center gap-2">
          {{-- Quick next-step (no JS extra routes assumed) --}}
          @php
            $next = $order->status === 'PENDING' ? 'PREPARING'
                  : ($order->status === 'PREPARING' ? 'READY'
                  : ($order->status === 'READY' ? 'READY' : 'PENDING'));
          @endphp
          <form action="{{ route('orders.status', $order) }}" method="POST" class="flex gap-2">
            @csrf @method('PATCH')
            <select name="status"
                    class="rounded-lg px-3 py-2 bg-neutral-100 dark:bg-neutral-800 text-sm">
              @foreach($statusMap as $k=>$m)
                <option value="{{ $k }}" @selected($order->status === $k)>{{ $m['label'] }}</option>
              @endforeach
            </select>
            <button class="rounded-lg px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-sm">حفظ</button>
          </form>

          <form action="{{ route('orders.status', $order) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="{{ $next }}">
            <button class="rounded-lg px-4 border text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">
              التالي → {{ $statusMap[$next]['label'] ?? $next }}
            </button>
          </form>

          <a target="_blank" href="{{ route('receipts.kitchen', $order) }}"
             class="rounded-lg px-4 border text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">
            طباعة تذكرة التحضير
          </a>

          <a target="_blank" href="{{ route('receipts.customer', $order) }}"
             class="rounded-lg px-4 border text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">
            🧾 فاتورة الزبون
          </a>
        </div>
      </div>
    @endforeach

    @if(!$found)
      <div class="col-span-full">
        <div class="rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-700 p-10 text-center bg-white dark:bg-neutral-900">
          <h3 class="font-semibold text-neutral-800 dark:text-neutral-200">لا توجد طلبات لهذا القسم حالياً</h3>
          <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">عند ورود طلبات جديدة ستظهر هنا.</p>
        </div>
      </div>
    @endif
  </div>
@endsection

@push('scripts')
<script>
  (function () {
    const $ = (s) => document.querySelector(s);
    const $$ = (s) => Array.from(document.querySelectorAll(s));

    // In-page search
    const q = $('#q');
    const cards = $$('.order-card');
    const applySearch = () => {
      const term = (q?.value || '').toLowerCase().trim();
      cards.forEach(card => {
        const text = (card.dataset.text || '').toLowerCase();
        card.style.display = term && !text.includes(term) ? 'none' : '';
      });
    };
    q?.addEventListener('input', applySearch);

    // Auto-refresh toggle with localStorage
    const auto = $('#autorefresh');
    const KEY = 'kitchen_autorefresh';
    if (localStorage.getItem(KEY) === '1') {
      auto.checked = true;
    }
    let timer = null;
    const start = () => {
      if (timer) return;
      timer = setInterval(() => location.reload(), 10000);
    };
    const stop = () => { if (timer) { clearInterval(timer); timer = null; } };

    auto?.addEventListener('change', () => {
      if (auto.checked) {
        localStorage.setItem(KEY, '1'); start();
      } else {
        localStorage.removeItem(KEY); stop();
      }
    });
    if (auto?.checked) start();

    // Quick filter by status from server (already exists via select),
    // also allow client-side filter by clicking the counters (optional).
    // We’ll just add a small enhancement: press "/" to focus search.
    window.addEventListener('keydown', (e) => {
      if (e.key === '/' && !/input|textarea|select/i.test(document.activeElement.tagName)) {
        e.preventDefault(); q?.focus();
      }
    });
  })();
</script>
@endpush
