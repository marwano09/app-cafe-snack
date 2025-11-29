@extends('layouts.app')
@section('title','لوحة التحكم')

@section('content')
@php
  $range     = request('range','today');
  $orders    = (int)($cards['orders'] ?? 0);
  $sales     = (float)($cards['sales'] ?? 0);
  $cancelled = (int)($cards['cancelled'] ?? 0);
  $avg       = (float)($cards['avg_ticket'] ?? 0);

  $delta = [
    'orders'    => $cards['orders_delta']     ?? null,
    'sales'     => $cards['sales_delta']      ?? null,
    'cancelled' => $cards['cancelled_delta']  ?? null,
    'avg'       => $cards['avg_ticket_delta'] ?? null,
  ];

  // Compat: use closures instead of arrow functions (works on PHP 7.0+)
  if (!function_exists('__pill_cls')) {
      function __pill_cls($d) {
          if (is_null($d)) return '';
          return $d >= 0
              ? 'text-emerald-700 bg-emerald-50 border-emerald-200 dark:text-emerald-300 dark:bg-emerald-900/30 dark:border-emerald-800'
              : 'text-rose-700 bg-rose-50 border-rose-200 dark:text-rose-300 dark:bg-rose-900/30 dark:border-rose-800';
      }
  }
  if (!function_exists('__arrow_txt')) {
      function __arrow_txt($d) {
          if (is_null($d)) return '';
          return $d >= 0 ? '↑' : '↓';
      }
  }

  $S = $byStatusToday ?? [];
  $statusForChart = [
    'جديدة'       => (int)($S['PENDING']   ?? 0),
    'قيد التحضير' => (int)($S['PREPARING'] ?? 0),
    'جاهزة'       => (int)($S['READY']     ?? 0),
    'ملغاة'       => (int)($S['CANCELLED'] ?? 0),
  ];
@endphp

{{-- Header / Theme + Range --}}
<div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
  <div>
    <h1 class="text-2xl font-semibold tracking-tight text-neutral-900 dark:text-neutral-100">لوحة التحكم</h1>
    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
      نظرة سريعة على الأداء والمبيعات لمدى:
      {{ $range==='today' ? 'اليوم' : ($range==='7d' ? 'آخر 7 أيام' : 'آخر 30 يومًا') }}.
    </p>
  </div>

  <div class="flex items-center gap-2">
    <form method="GET" class="flex items-center gap-2">
      <select name="range" class="rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm">
        <option value="today" {{ $range==='today'?'selected':'' }}>اليوم</option>
        <option value="7d"    {{ $range==='7d'?'selected':'' }}>آخر 7 أيام</option>
        <option value="30d"   {{ $range==='30d'?'selected':'' }}>آخر 30 يومًا</option>
      </select>
      <button class="rounded-xl border px-4 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">تطبيق</button>
    </form>

    <button id="themeToggle"
            class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800"
            type="button" aria-label="Toggle theme">
      <span class="hidden dark:inline-flex items-center gap-1">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M21.64 13A1 1 0 0 0 20 12a8 8 0 1 1-8-8 1 1 0 0 0 1-1 1 1 0 0 0-1.36-.94 10 10 0 1 0 10 10Z"/></svg>
        داكن
      </span>
      <span class="dark:hidden inline-flex items-center gap-1">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.76 4.84 5.34 3.42 3.92 4.84l1.42 1.42L6.76 4.84ZM1 13h3v-2H1v2Zm10 10h2v-3h-2v3ZM11 3h2V0h-2v3Zm9.66 1.42-1.42 1.42 1.42 1.42 1.42-1.42-1.42-1.42ZM19 13h4v-2h-4v2ZM6.76 19.16l-1.42 1.42 1.42 1.42 1.42-1.42-1.42-1.42ZM17.24 19.16l1.42 1.42 1.42-1.42-1.42-1.42-1.42 1.42ZM12 6a6 6 0 1 0 0 12A6 6 0 0 0 12 6Z"/></svg>
        فاتح
      </span>
    </button>
  </div>
</div>

{{-- KPI Cards --}}
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-6">
  {{-- Orders --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="text-sm opacity-70">عدد الطلبات ({{ $range==='today'?'اليوم':($range==='7d'?'7 أيام':'30 يوم') }})</div>
      <div class="size-9 grid place-items-center rounded-xl bg-neutral-100 dark:bg-neutral-800">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M6 7v10m12-10v10M3 17h18"/></svg>
      </div>
    </div>
    <div class="mt-3 flex items-end gap-2">
      <div class="text-3xl font-extrabold tabular-nums">{{ $orders }}</div>
      @if(!is_null($delta['orders']))
        <span class="text-[11px] px-2 py-0.5 rounded-full border {{ __pill_cls($delta['orders']) }}">{{ __arrow_txt($delta['orders']) }} {{ number_format(abs($delta['orders']),0) }}%</span>
      @endif
    </div>
  </div>

  {{-- Sales --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="text-sm opacity-70">إجمالي المبيعات ({{ $range==='today'?'اليوم':($range==='7d'?'7 أيام':'30 يوم') }})</div>
      <div class="size-9 grid place-items-center rounded-xl bg-neutral-100 dark:bg-neutral-800">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v8m-6 0h12"/></svg>
      </div>
    </div>
    <div class="mt-3 flex items-end gap-2">
      <div class="text-3xl font-extrabold tabular-nums">DH {{ number_format($sales,2) }}</div>
      @if(!is_null($delta['sales']))
        <span class="text-[11px] px-2 py-0.5 rounded-full border {{ __pill_cls($delta['sales']) }}">{{ __arrow_txt($delta['sales']) }} {{ number_format(abs($delta['sales']),0) }}%</span>
      @endif
    </div>
  </div>

  {{-- Cancelled --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="text-sm opacity-70">ملغاة ({{ $range==='today'?'اليوم':($range==='7d'?'7 أيام':'30 يوم') }})</div>
      <div class="size-9 grid place-items-center rounded-xl bg-neutral-100 dark:bg-neutral-800">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
      </div>
    </div>
    <div class="mt-3 flex items-end gap-2">
      <div class="text-3xl font-extrabold tabular-nums">{{ $cancelled }}</div>
      @if(!is_null($delta['cancelled']))
        <span class="text-[11px] px-2 py-0.5 rounded-full border {{ __pill_cls($delta['cancelled']) }}">{{ __arrow_txt($delta['cancelled']) }} {{ number_format(abs($delta['cancelled']),0) }}%</span>
      @endif
    </div>
  </div>

  {{-- Avg Ticket --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="text-sm opacity-70">متوسط الفاتورة</div>
      <div class="size-9 grid place-items-center rounded-xl bg-neutral-100 dark:bg-neutral-800">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12h16M4 8h10M4 16h7"/></svg>
      </div>
    </div>
    <div class="mt-3 flex items-end gap-2">
      <div class="text-3xl font-extrabold tabular-nums">DH {{ number_format($avg,2) }}</div>
      @if(!is_null($delta['avg']))
        <span class="text-[11px] px-2 py-0.5 rounded-full border {{ __pill_cls($delta['avg']) }}">{{ __arrow_txt($delta['avg']) }} {{ number_format(abs($delta['avg']),0) }}%</span>
      @endif
    </div>
  </div>
</div>

{{-- Sales + Orders by status charts --}}
<div class="grid gap-4 lg:grid-cols-3 mb-6">
  <div class="lg:col-span-2 rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 backdrop-blur p-5">
    <div class="flex items-center justify-between mb-3">
      <h3 class="font-semibold text-neutral-900 dark:text-neutral-100">المبيعات حسب اليوم</h3>
      <div class="flex items-center gap-2">
        <button id="exportCsv" class="text-xs rounded-lg border px-2.5 py-1 hover:bg-neutral-100 dark:hover:bg-neutral-800">تصدير CSV</button>
      </div>
    </div>
    <div class="h-64">
      <canvas id="salesChart"
              data-labels='@json($chartLabels ?? [])'
              data-values='@json($chartData ?? [])'></canvas>
    </div>
  </div>

  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 backdrop-blur p-5">
    <h3 class="font-semibold text-neutral-900 dark:text-neutral-100 mb-3">
      الطلبات حسب الحالة ({{ $range==='today'?'اليوم':($range==='7d'?'7 أيام':'30 يوم') }})
    </h3>
    <div class="h-64">
      <canvas id="statusChart"
              data-status='@json(array_values($statusForChart))'
              data-labels='@json(array_keys($statusForChart))'></canvas>
    </div>
  </div>
</div>

{{-- Quick actions --}}
<div class="grid gap-4 md:grid-cols-3 mb-8">
  @role('waiter|manager')
  <a href="{{ route('orders.create') }}"
     class="group rounded-2xl border border-emerald-700/30 bg-emerald-900/30 hover:bg-emerald-900/40 text-emerald-50 p-5 transition">
    <div class="flex items-center justify-between">
      <div class="text-lg font-bold">طلب جديد</div>
      <div class="size-9 grid place-items-center rounded-xl bg-emerald-800/60">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6"/></svg>
      </div>
    </div>
    <p class="mt-2 text-sm opacity-90">ابدأ بإدخال طلب للزبون (نادل/مدير).</p>
  </a>
  @endrole

  @role('kitchen|bar|manager')
  <a href="{{ route('kitchen.index') }}"
     class="group rounded-2xl border border-amber-700/30 bg-amber-900/30 hover:bg-amber-900/40 text-amber-50 p-5 transition">
    <div class="flex items-center justify-between">
      <div class="text-lg font-bold">المطبخ/البار</div>
      <div class="size-9 grid place-items-center rounded-xl bg-amber-800/60">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M7 6v12m10-12v12M4 18h16"/></svg>
      </div>
    </div>
    <p class="mt-2 text-sm opacity-90">تتبّع حالات التحضير وتغيير الحالة فوراً.</p>
  </a>
  @endrole

  @role('manager')
  <a href="{{ route('menu.index') }}"
     class="group rounded-2xl border border-sky-700/30 bg-sky-900/30 hover:bg-sky-900/40 text-sky-50 p-5 transition">
    <div class="flex items-center justify-between">
      <div class="text-lg font-bold">إدارة القائمة</div>
      <div class="size-9 grid place-items-center rounded-xl bg-sky-800/60">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h10"/></svg>
      </div>
    </div>
    <p class="mt-2 text-sm opacity-90">إضافة/تعديل/حذف أصناف المشروبات والمأكولات.</p>
  </a>
  @endrole
</div>

{{-- Latest orders + performance tables --}}
@php
  $byWaiterCol = collect($byWaiter ?? []);
  $topItemsCol = collect($topItems ?? []);
@endphp

<div class="grid gap-4 lg:grid-cols-2 mb-8">
  {{-- Latest orders --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 backdrop-blur p-5">
    <div class="flex items-center justify-between mb-3">
      <h3 class="font-semibold text-neutral-900 dark:text-neutral-100">أحدث الطلبات</h3>
      <a href="{{ route('orders.index') }}" class="text-xs text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200">عرض الكل</a>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200">
          <tr>
            <th class="p-2 text-right">#</th>
            <th class="p-2">النادل</th>
            <th class="p-2">الحالة</th>
            <th class="p-2">المجموع</th>
            <th class="p-2">التاريخ</th>
            <th class="p-2">تفاصيل</th>
          </tr>
        </thead>
        <tbody class="text-neutral-800 dark:text-neutral-100">
          @if(!empty($latestOrders) && count($latestOrders) > 0)
            @foreach($latestOrders as $o)
              @php
                $badge = [
                  'PENDING'   => 'border-neutral-300 text-neutral-700 dark:border-neutral-700 dark:text-neutral-300',
                  'PREPARING' => 'border-amber-300 text-amber-700 dark:border-amber-700 dark:text-amber-300',
                  'READY'     => 'border-emerald-300 text-emerald-700 dark:border-emerald-700 dark:text-emerald-300',
                  'CANCELLED' => 'border-rose-300 text-rose-700 dark:border-rose-700 dark:text-rose-300',
                ][$o->status] ?? 'border-neutral-300 text-neutral-600 dark:border-neutral-700 dark:text-neutral-300';
              @endphp
              <tr class="border-t border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50/60 dark:hover:bg-neutral-800/60">
                <td class="p-2 tabular-nums">{{ $o->id }}</td>
                <td class="p-2">{{ optional($o->waiter)->name ?? '—' }}</td>
                <td class="p-2">
                  <span class="text-[11px] rounded-full px-2 py-0.5 border {{ $badge }}">
                    {{ ['PENDING'=>'جديدة','PREPARING'=>'قيد التحضير','READY'=>'جاهزة','CANCELLED'=>'ملغاة'][$o->status] ?? $o->status }}
                  </span>
                </td>
                <td class="p-2 tabular-nums">DH {{ number_format((float)$o->total,2) }}</td>
                <td class="p-2">{{ $o->created_at->format('H:i Y-m-d') }}</td>
                <td class="p-2">
                  <a class="text-sky-600 hover:underline dark:text-sky-400" href="{{ route('orders.show',$o) }}">عرض</a>
                </td>
              </tr>
            @endforeach
          @else
            <tr><td colspan="6" class="p-3 text-center opacity-70">لا توجد بيانات بعد.</td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>

  {{-- Waiter performance --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 backdrop-blur p-5">
    <h3 class="font-semibold mb-3 text-neutral-900 dark:text-neutral-100">أداء النادل</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200">
          <tr><th class="p-2 text-right">النادل</th><th class="p-2">عدد الطلبات</th><th class="p-2">الإيراد</th></tr>
        </thead>
        <tbody class="text-neutral-800 dark:text-neutral-100">
          @if($byWaiterCol->count())
            @foreach($byWaiterCol as $w)
              <tr class="border-t border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50/60 dark:hover:bg-neutral-800/60">
                <td class="p-2">{{ data_get($w, 'name', '—') }}</td>
                <td class="p-2 tabular-nums">{{ (int) data_get($w, 'cnt', 0) }}</td>
                <td class="p-2 tabular-nums">DH {{ number_format((float) data_get($w, 'revenue', 0), 2) }}</td>
              </tr>
            @endforeach
          @else
            <tr><td class="p-3 text-center opacity-70" colspan="3">لا توجد بيانات بعد.</td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>

  {{-- Top items --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 backdrop-blur p-5">
    <h3 class="font-semibold mb-3 text-neutral-900 dark:text-neutral-100">الأصناف الأكثر مبيعاً</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200">
          <tr><th class="p-2 text-right">الصنف</th><th class="p-2">الكمية</th><th class="p-2">الإيراد</th></tr>
        </thead>
        <tbody class="text-neutral-800 dark:text-neutral-100">
          @if($topItemsCol->count())
            @foreach($topItemsCol as $i)
              <tr class="border-t border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50/60 dark:hover:bg-neutral-800/60">
                <td class="p-2">{{ data_get($i, 'name', '—') }}</td>
                <td class="p-2 tabular-nums">{{ (int) data_get($i, 'qty', 0) }}</td>
                <td class="p-2 tabular-nums">DH {{ number_format((float) data_get($i, 'revenue', 0), 2) }}</td>
              </tr>
            @endforeach
          @else
            <tr><td class="p-3 text-center opacity-70" colspan="3">لا توجد بيانات بعد.</td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Load Chart.js if missing
  (function ensureChart() {
    if (window.Chart) return;
    var s = document.createElement('script');
    s.src = "https://cdn.jsdelivr.net/npm/chart.js";
    s.async = true;
    document.head.appendChild(s);
  })();

  // Theme toggle with persistence
  (function themeToggle() {
    var root = document.documentElement;
    var KEY  = 'app_theme';
    var btn  = document.getElementById('themeToggle');
    var apply = function (mode) { mode === 'dark' ? root.classList.add('dark') : root.classList.remove('dark'); };
    var stored = localStorage.getItem(KEY);
    if (stored) apply(stored);
    btn && btn.addEventListener('click', function () {
      var to = root.classList.contains('dark') ? 'light' : 'dark';
      localStorage.setItem(KEY, to);
      apply(to);
      window.dispatchEvent(new CustomEvent('theme-change', { detail: to }));
    });
    window.addEventListener('keydown', function (e) { if (e.shiftKey && (e.key === 'D' || e.key === 'd')) btn && btn.click(); });
  })();

  // Charts init (no modern syntax required)
  (function chartsInit() {
    function start() {
      if (!window.Chart) { setTimeout(start, 50); return; }

      var salesEl = document.getElementById('salesChart');
      var labels  = [];
      var values  = [];
      try {
        labels = JSON.parse((salesEl && salesEl.dataset.labels) || '[]');
        values = JSON.parse((salesEl && salesEl.dataset.values) || '[]');
      } catch (e) {}

      var statusEl = document.getElementById('statusChart');
      var sLabels = [], sValues = [];
      try {
        sLabels = JSON.parse((statusEl && statusEl.dataset.labels) || '[]');
        sValues = JSON.parse((statusEl && statusEl.dataset.status) || '[]');
      } catch (e) {}

      var isDark = document.documentElement.classList.contains('dark');
      var lineColors = isDark
        ? { border: 'rgba(147,197,253,1)', areaStart: 'rgba(59,130,246,0.25)', areaEnd: 'rgba(59,130,246,0.02)', tick: '#9ca3af', grid: 'rgba(255,255,255,0.06)', legend: '#e5e7eb' }
        : { border: 'rgba(37,99,235,1)',   areaStart: 'rgba(59,130,246,0.35)', areaEnd: 'rgba(59,130,246,0.05)', tick: '#6b7280', grid: 'rgba(0,0,0,0.06)',     legend: '#374151' };

      var doughnutColors = isDark
        ? ['#94a3b8','#f59e0b','#34d399','#f87171']
        : ['#64748b','#f59e0b','#10b981','#ef4444'];

      // Gradient
      var ctx = salesEl.getContext('2d');
      var grad = ctx.createLinearGradient(0, 0, 0, salesEl.height);
      grad.addColorStop(0, lineColors.areaStart);
      grad.addColorStop(1, lineColors.areaEnd);

      var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'المبيعات (DH)',
            data: values,
            tension: 0.35,
            fill: true,
            backgroundColor: grad,
            borderColor: lineColors.border,
            borderWidth: 2,
            pointRadius: 0,
            hoverRadius: 3
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: true, labels: { color: lineColors.legend } },
            tooltip: { callbacks: { label: function (c) { return 'DH ' + Number(c.parsed.y).toFixed(2); } } }
          },
          interaction: { mode: 'index', intersect: false },
          scales: {
            x: { grid: { display: false }, ticks: { color: lineColors.tick } },
            y: { beginAtZero: true, ticks: { precision: 0, color: lineColors.tick }, grid: { color: lineColors.grid } }
          }
        }
      });

      var statusChart = null;
      if (statusEl) {
        statusChart = new Chart(statusEl.getContext('2d'), {
          type: 'doughnut',
          data: { labels: sLabels, datasets: [{ data: sValues, backgroundColor: doughnutColors, borderWidth: 0 }] },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { color: lineColors.legend } },
                       tooltip: { callbacks: { label: function (c) { return c.label + ': ' + c.parsed; } } } },
            cutout: '65%'
          }
        });
      }

      // CSV Export
      var btnCsv = document.getElementById('exportCsv');
      btnCsv && btnCsv.addEventListener('click', function () {
        if (!labels.length) return;
        var rows = [['Date','Sales (DH)']];
        for (var i=0; i<labels.length; i++) rows.push([labels[i], values[i] || 0]);
        var csv = rows.map(function (r) { return r.map(function (x) { return '"' + String(x).replace(/"/g,'""') + '"'; }).join(','); }).join('\n');
        var blob = new Blob([csv], {type:'text/csv;charset=utf-8;'});
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'sales.csv';
        a.click();
      });

      // Re-theme
      window.addEventListener('theme-change', function (e) {
        var dark = e.detail === 'dark';
        var lc = dark
          ? { border: 'rgba(147,197,253,1)', areaStart: 'rgba(59,130,246,0.25)', areaEnd: 'rgba(59,130,246,0.02)', tick: '#9ca3af', grid: 'rgba(255,255,255,0.06)', legend: '#e5e7eb' }
          : { border: 'rgba(37,99,235,1)',   areaStart: 'rgba(59,130,246,0.35)', areaEnd: 'rgba(59,130,246,0.05)', tick: '#6b7280', grid: 'rgba(0,0,0,0.06)',     legend: '#374151' };

        var grad2 = ctx.createLinearGradient(0, 0, 0, salesEl.height);
        grad2.addColorStop(0, lc.areaStart);
        grad2.addColorStop(1, lc.areaEnd);

        salesChart.data.datasets[0].backgroundColor = grad2;
        salesChart.data.datasets[0].borderColor = lc.border;
        salesChart.options.scales.x.ticks.color = lc.tick;
        salesChart.options.scales.y.ticks.color = lc.tick;
        salesChart.options.scales.y.grid.color  = lc.grid;
        salesChart.options.plugins.legend.labels.color = lc.legend;
        salesChart.update();

        if (statusChart) {
          statusChart.options.plugins.legend.labels.color = lc.legend;
          statusChart.update();
        }
      });
    }
    start();
  })();
</script>
@endpush
