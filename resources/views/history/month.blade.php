@extends('layouts.app')
@section('title','تقويم الطلبات')

@section('content')
@php
  $monthDate  = \Carbon\Carbon::createFromDate($year,$month,1);
  $monthTitle = $monthDate->locale('ar')->translatedFormat('F Y');
  $prev       = $monthDate->clone()->subMonth();
  $next       = $monthDate->clone()->addMonth();
  $weekdays   = ['الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت','الأحد'];

  $totalOrders  = collect($byDay)->sum('orders');
  $totalRevenue = collect($byDay)->sum('revenue');

  $chartLabels = [];
  $chartData   = [];
  for ($d=1; $d <= $daysInMon; $d++) {
    $ds = $monthDate->clone()->day($d)->toDateString();
    $chartLabels[] = (string)$d;
    $chartData[]   = (float)($byDay[$ds]['revenue'] ?? 0);
  }
@endphp

{{-- Header --}}
<div class="sticky top-0 z-20 -mx-4 px-4 md:-mx-6 md:px-6 py-4
            bg-white/90 dark:bg-sky-950/90 backdrop-blur
            border-b border-blue-200 dark:border-blue-800">
  <div class="flex flex-wrap items-center justify-between gap-3">
    <div>
      <h1 class="text-3xl font-extrabold leading-tight tracking-tight">
        <span class="bg-gradient-to-r from-sky-600 via-emerald-500 to-blue-600 bg-clip-text text-transparent">
          {{ $monthTitle }}
        </span>
      </h1>
      <div class="mt-1 text-sm text-blue-600 dark:text-blue-300">
        إجمالي الشهر: <strong>{{ $totalOrders }}</strong> طلب •
        الإيراد:
        <strong class="text-emerald-600 dark:text-emerald-400">
          DH {{ number_format($totalRevenue,2) }}
        </strong>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('history.year',$year) }}"
         class="rounded-xl px-3.5 py-2 text-sm font-medium border border-blue-500 text-blue-600 dark:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition">
        📆 عرض السنة
      </a>
      <a href="{{ route('history.month.view',[$prev->year,$prev->month]) }}"
         class="rounded-xl px-3 py-2 text-sm font-medium border border-blue-300 dark:border-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition">
        ◀︎ السابق
      </a>
      <a href="{{ route('history.month.view',[$next->year,$next->month]) }}"
         class="rounded-xl px-3 py-2 text-sm font-medium border border-blue-300 dark:border-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition">
        التالي ▶︎
      </a>
    </div>
  </div>
</div>

{{-- Summary --}}
<div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
  <div class="rounded-2xl border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-sky-900 p-4">
    <div class="text-xs text-blue-500 dark:text-blue-300">طلبات هذا الشهر</div>
    <div class="mt-1 text-2xl font-extrabold">{{ $totalOrders }}</div>
  </div>
  <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900 p-4">
    <div class="text-xs text-emerald-600 dark:text-emerald-300">إيراد هذا الشهر</div>
    <div class="mt-1 text-2xl font-extrabold text-emerald-700 dark:text-emerald-400">
      DH {{ number_format($totalRevenue,2) }}
    </div>
  </div>
  <div class="rounded-2xl border border-sky-200 dark:border-sky-700 bg-sky-50 dark:bg-sky-900 p-4">
    <div class="text-xs text-sky-600 dark:text-sky-300">متوسط الطلبات يومياً</div>
    <div class="mt-1 text-2xl font-extrabold">
      {{ number_format($daysInMon ? $totalOrders/$daysInMon : 0,1) }}
    </div>
  </div>
  <div class="rounded-2xl border border-indigo-200 dark:border-indigo-700 bg-indigo-50 dark:bg-indigo-900 p-4">
    <div class="text-xs text-indigo-600 dark:text-indigo-300">متوسط الإيراد يومياً</div>
    <div class="mt-1 text-2xl font-extrabold">
      DH {{ number_format($daysInMon ? $totalRevenue/$daysInMon : 0,2) }}
    </div>
  </div>
</div>

{{-- Chart --}}
<div class="mt-6 rounded-2xl border border-sky-200 dark:border-sky-700 bg-sky-50 dark:bg-sky-950 p-4">
  <h3 class="font-semibold text-sky-700 dark:text-sky-300">الإيراد اليومي</h3>
  <div class="mt-3"><canvas id="monthChart" height="120"></canvas></div>
</div>

{{-- Week header --}}
<div class="mt-6 grid grid-cols-7 gap-2 text-[13px] font-semibold text-sky-600 dark:text-sky-300">
  @foreach($weekdays as $w)
    <div class="text-center">{{ $w }}</div>
  @endforeach
</div>

{{-- Calendar grid --}}
<div class="grid grid-cols-7 gap-3 mt-2">
  @for($i=1; $i < $startWeek; $i++)
    <div></div>
  @endfor

  @for($d=1; $d <= $daysInMon; $d++)
    @php
      $dateStr = $monthDate->clone()->day($d)->toDateString();
      $stats   = $byDay[$dateStr] ?? ['orders'=>0,'revenue'=>0];
      $orders  = $stats['orders'];
      $rev     = $stats['revenue'];
      $isToday = $dateStr === now()->toDateString();

      $tone =
        $rev <= 0   ? 'bg-white dark:bg-sky-950'
      : ($rev < 200 ? 'bg-emerald-50 dark:bg-emerald-900/40'
      : ($rev < 600 ? 'bg-emerald-100 dark:bg-emerald-800/50'
                    : 'bg-emerald-200 dark:bg-emerald-700/60'));
    @endphp

    <a href="{{ route('history.day',[$year,$month,$d]) }}"
       class="group relative rounded-2xl border {{ $tone }}
              {{ $isToday ? 'border-emerald-500 ring-2 ring-emerald-400/70' : 'border-blue-200 dark:border-blue-800' }}
              p-3 hover:shadow-lg transition-all">
      <div class="flex items-start justify-between">
        <span class="text-xs text-sky-500">اليوم</span>
        <span class="font-bold text-lg {{ $isToday ? 'text-emerald-700 dark:text-emerald-300' : 'text-blue-700 dark:text-blue-200' }}">
          {{ $d }}
        </span>
      </div>
      <div class="mt-2 text-sm">
        <div>📦 طلبات: <strong>{{ $orders }}</strong></div>
        <div>💰 إيراد: <strong class="text-emerald-600 dark:text-emerald-400">DH {{ number_format($rev,2) }}</strong></div>
      </div>
    </a>
  @endfor
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('monthChart');
if (ctx) {
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: @json($chartLabels),
      datasets: [{
        data: @json($chartData),
        borderColor: '#10b981',
        backgroundColor: '#10b98122',
        fill: true,
        tension: 0.3,
        borderWidth: 2,
        pointRadius: 3,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
}
</script>
@endpush
