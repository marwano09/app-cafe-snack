@extends('layouts.app')
@section('title','لوحة التحكم')

@section('content')
{{-- ===== KPIs / Cards ===== --}}
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-6">
  {{-- Orders Today --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="text-sm opacity-70">عدد الطلبات (اليوم)</div>
      <div class="size-9 grid place-items-center rounded-xl bg-neutral-100 dark:bg-neutral-800">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M6 7v10m12-10v10M3 17h18"/>
        </svg>
      </div>
    </div>
    <div class="mt-3 text-3xl font-extrabold tabular-nums">{{ $cards['orders'] ?? 0 }}</div>
  </div>

  {{-- Sales Today --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="text-sm opacity-70">إجمالي المبيعات (اليوم)</div>
      <div class="size-9 grid place-items-center rounded-xl bg-neutral-100 dark:bg-neutral-800">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v8m-6 0h12"/>
        </svg>
      </div>
    </div>
    <div class="mt-3 text-3xl font-extrabold tabular-nums">DH {{ number_format((float)($cards['sales'] ?? 0),2) }}</div>
  </div>

  {{-- Cancelled --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="text-sm opacity-70">ملغاة (اليوم)</div>
      <div class="size-9 grid place-items-center rounded-xl bg-neutral-100 dark:bg-neutral-800">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </div>
    </div>
    <div class="mt-3 text-3xl font-extrabold tabular-nums">{{ $cards['cancelled'] ?? 0 }}</div>
  </div>

  {{-- Avg Ticket --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="text-sm opacity-70">متوسط الفاتورة</div>
      <div class="size-9 grid place-items-center rounded-xl bg-neutral-100 dark:bg-neutral-800">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12h16M4 8h10M4 16h7"/>
        </svg>
      </div>
    </div>
    <div class="mt-3 text-3xl font-extrabold tabular-nums">DH {{ number_format((float)($cards['avg_ticket'] ?? 0),2) }}</div>
  </div>
</div>

{{-- ===== Sales chart ===== --}}
<div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 backdrop-blur p-5 mb-6">
  <div class="flex items-center justify-between mb-3">
    <h3 class="font-semibold">المبيعات اليومية</h3>
    <div class="text-xs opacity-70">قابلة للتكبير/التمرير</div>
  </div>
  <div class="h-64">
    <canvas id="salesChart"></canvas>
  </div>
</div>

{{-- ===== Quick actions ===== --}}
<div class="grid gap-4 md:grid-cols-3 mb-8">
  @role('waiter|manager')
  <a href="{{ route('orders.create') }}"
     class="group rounded-2xl border border-emerald-700/30 bg-emerald-900/30 hover:bg-emerald-900/40 text-emerald-50 p-5 transition">
    <div class="flex items-center justify-between">
      <div class="text-lg font-bold">طلب جديد</div>
      <div class="size-9 grid place-items-center rounded-xl bg-emerald-800/60">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6"/>
        </svg>
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
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M7 6v12m10-12v12M4 18h16"/>
        </svg>
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
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h10"/>
        </svg>
      </div>
    </div>
    <p class="mt-2 text-sm opacity-90">إضافة/تعديل/حذف أصناف المشروبات والمأكولات.</p>
  </a>
  @endrole
</div>
{{-- ===== Mid-page statistics: orders by status + latest orders ===== --}}
<div class="grid gap-4 lg:grid-cols-2 mb-8">
  {{-- Orders by status (today) --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 backdrop-blur p-5">
    <h3 class="font-semibold mb-3">الطلبات حسب الحالة (اليوم)</h3>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      @php $S = $byStatusToday ?? []; @endphp
      @foreach ([
        'PENDING'   => 'جديدة',
        'PREPARING' => 'قيد التحضير',
        'READY'     => 'جاهزة',
        'CANCELLED' => 'ملغاة',
      ] as $key => $label)
        <div class="rounded-xl border border-neutral-200/60 dark:border-neutral-800/80 p-4 text-center">
          <div class="text-xs opacity-70 mb-1">{{ $label }}</div>
          <div class="text-2xl font-bold tabular-nums">{{ (int)($S[$key] ?? 0) }}</div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Latest orders --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 backdrop-blur p-5">
    <div class="flex items-center justify-between mb-3">
      <h3 class="font-semibold">أحدث الطلبات</h3>
      <a href="{{ route('orders.index') }}" class="text-xs opacity-70 hover:opacity-100">عرض الكل</a>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 dark:bg-neutral-800">
          <tr>
            <th class="p-2 text-right">#</th>
            <th class="p-2">النادل</th>
            <th class="p-2">الحالة</th>
            <th class="p-2">المجموع</th>
            <th class="p-2">التاريخ</th>
            <th class="p-2">تفاصيل</th>
          </tr>
        </thead>
        <tbody>
          @foreach(($latestOrders ?? []) as $o)
            <tr class="border-t">
              <td class="p-2">{{ $o->id }}</td>
              <td class="p-2">{{ optional($o->waiter)->name ?? '—' }}</td>
              <td class="p-2">{{ $o->status }}</td>
              <td class="p-2">DH {{ number_format((float)$o->total,2) }}</td>
              <td class="p-2">{{ $o->created_at->format('H:i Y-m-d') }}</td>
              <td class="p-2">
                <a class="text-sky-500 hover:underline" href="{{ route('orders.show',$o) }}">عرض</a>
              </td>
            </tr>
          @endforeach
          @if(empty($latestOrders) || count($latestOrders) === 0)
            <tr><td colspan="6" class="p-3 text-center opacity-70">لا توجد بيانات بعد.</td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- ===== Performance tables ===== --}}
@php($byWaiter  = collect($byWaiter ?? []))
@php($topItems  = collect($topItems ?? []))

<div class="grid gap-4 lg:grid-cols-2">
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 backdrop-blur p-5">
    <h3 class="font-semibold mb-3">أداء النادل</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 dark:bg-neutral-800">
          <tr><th class="p-2 text-right">النادل</th><th class="p-2">عدد الطلبات</th><th class="p-2">الإيراد</th></tr>
        </thead>
        <tbody>
          @forelse($byWaiter as $w)
            <tr class="border-t">
              <td class="p-2">{{ data_get($w, 'name', '—') }}</td>
              <td class="p-2">{{ (int) data_get($w, 'cnt', 0) }}</td>
              <td class="p-2">DH {{ number_format((float) data_get($w, 'revenue', 0), 2) }}</td>
            </tr>
          @empty
            <tr><td class="p-3 text-center opacity-70" colspan="3">لا توجد بيانات بعد.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 backdrop-blur p-5">
    <h3 class="font-semibold mb-3">الأصناف الأكثر مبيعاً</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 dark:bg-neutral-800">
          <tr><th class="p-2 text-right">الصنف</th><th class="p-2">الكمية</th><th class="p-2">الإيراد</th></tr>
        </thead>
        <tbody>
          @forelse($topItems as $i)
            <tr class="border-t">
              <td class="p-2">{{ data_get($i, 'name', '—') }}</td>
              <td class="p-2">{{ (int) data_get($i, 'qty', 0) }}</td>
              <td class="p-2">DH {{ number_format((float) data_get($i, 'revenue', 0), 2) }}</td>
            </tr>
          @empty
            <tr><td class="p-3 text-center opacity-70" colspan="3">لا توجد بيانات بعد.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  (function () {
    const el = document.getElementById('salesChart');
    if (!el || !window.Chart) return;

    const labels = @json($chartLabels ?? []);
    const data   = @json($chartData ?? []);

    new Chart(el.getContext('2d'), {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'المبيعات (DH)',
          data,
          tension: 0.35,
          fill: true,
          borderWidth: 2,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: true } },
        interaction: { mode: 'index', intersect: false },
        scales: {
          x: { grid: { display: false } },
          y: { beginAtZero: true, ticks: { precision: 0 } }
        }
      }
    });
  })();
</script>
@endpush
