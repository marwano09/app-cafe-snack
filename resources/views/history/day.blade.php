@extends('layouts.app')
@section('title','تفاصيل اليوم: '.$date)

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
  <div>
    <div class="text-sm text-gray-500 dark:text-gray-400">📅 التاريخ</div>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $date }}</h1>
  </div>
  <div>
    <a class="rounded-full border border-blue-500 text-blue-600 dark:text-blue-400 px-4 py-2 text-sm hover:bg-blue-50 dark:hover:bg-blue-900 transition" href="{{ route('history.month.view', [$year,$month]) }}">↩️ الرجوع للشهر</a>
  </div>
</div>

{{-- KPIs --}}
<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
  @foreach([
    ['label' => 'عدد الطلبات', 'value' => $totals['orders'], 'color' => 'text-indigo-600'],
    ['label' => 'الإيراد', 'value' => 'DH '.number_format($totals['revenue'],2), 'color' => 'text-green-600'],
    ['label' => 'ملغاة', 'value' => $totals['cancel'], 'color' => 'text-red-500'],
    ['label' => 'متوسط الفاتورة', 'value' => 'DH '.number_format($totals['avg'],2), 'color' => 'text-amber-600'],
  ] as $kpi)
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-5 bg-gradient-to-br from-white to-gray-50 dark:from-neutral-900 dark:to-neutral-800 shadow-sm hover:shadow-md transition-all duration-300">
      <div class="text-sm text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</div>
      <div class="mt-2 text-2xl font-extrabold {{ $kpi['color'] }} dark:text-white">{{ $kpi['value'] }}</div>
    </div>
  @endforeach
</div>

{{-- Hourly chart --}}
<div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-5 bg-white dark:bg-neutral-900 mb-8">
  <div class="flex items-center justify-between mb-4">
    <h3 class="font-semibold text-gray-800 dark:text-white">📈 الإيراد بالساعة</h3>
    <div class="text-xs text-gray-500 dark:text-gray-400">لـ {{ $date }}</div>
  </div>
  <canvas id="dayChart" height="120"></canvas>
</div>

{{-- Orders table --}}
<div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-neutral-900 overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-neutral-50 dark:bg-neutral-800 text-gray-700 dark:text-gray-200">
      <tr>
        <th class="p-3 text-right">#</th>
        <th class="p-3">الوقت</th>
        <th class="p-3">النادل</th>
        <th class="p-3">الحالة</th>
        <th class="p-3">المجموع</th>
        <th class="p-3">تفاصيل</th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $o)
        <tr class="border-t border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-neutral-800 transition">
          <td class="p-3">{{ $o->id }}</td>
          <td class="p-3">{{ $o->created_at->format('H:i') }}</td>
          <td class="p-3">{{ $o->waiter->name ?? '—' }}</td>
          <td class="p-3">
            <span class="text-xs font-medium
              {{ $o->status === 'READY' ? 'text-emerald-600' :
                 ($o->status === 'CANCELLED' ? 'text-red-600' :
                 ($o->status === 'PREPARING' ? 'text-amber-600' : 'text-gray-500')) }}">
              {{ $o->status }}
            </span>
          </td>
          <td class="p-3">DH {{ number_format((float)$o->total,2) }}</td>
          <td class="p-3">
            <details>
              <summary class="cursor-pointer text-xs underline text-blue-600 dark:text-blue-400">عرض البنود</summary>
              <ul class="list-disc ms-5 mt-1 text-gray-700 dark:text-gray-300">
                @foreach($o->items as $it)
                  <li>{{ $it->menuItem->name ?? '—' }} × {{ $it->quantity }} — DH {{ number_format($it->price,2) }}</li>
                @endforeach
              </ul>
            </details>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">لا توجد طلبات في هذا اليوم.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const el = document.getElementById('dayChart');
  if (!el || !window.Chart) return;
  const labels = @json($chartLabels);
  const data   = @json($chartData);

  new Chart(el.getContext('2d'), {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'DH',
        data,
        backgroundColor: '#3b82f6',
        borderRadius: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: { beginAtZero: true }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });
})();
</script>
@endpush