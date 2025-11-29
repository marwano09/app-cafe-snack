@props([
  'href',
  'day' => 1,
  'orders' => 0,
  'revenue' => 0.0,
  'isToday' => false,
])

@php
  $toneLight = match (true) {
      $revenue <= 0      => 'from-white to-blue-50 dark:from-slate-950 dark:to-slate-900',
      $revenue < 200     => 'from-emerald-50 to-emerald-100 dark:from-emerald-900/40 dark:to-emerald-800/40',
      $revenue < 600     => 'from-emerald-100 to-emerald-200 dark:from-emerald-800/60 dark:to-emerald-700/60',
      default            => 'from-emerald-200 to-emerald-300 dark:from-emerald-700/70 dark:to-emerald-600/70',
  };

  $border = $isToday
    ? 'border-emerald-500 ring-2 ring-emerald-400/70'
    : 'border-blue-200 dark:border-blue-800';

  $gloss = 'before:absolute before:inset-0 before:bg-gradient-to-b before:from-white/0 before:to-white/10 dark:before:to-white/5 before:opacity-0 hover:before:opacity-100 before:transition';
@endphp

<a href="{{ $href }}"
   class="relative rounded-2xl border {{ $border }}
          bg-gradient-to-br {{ $toneLight }} p-3
          shadow-sm hover:shadow-lg transition-all
          hover:scale-[1.01] {{ $gloss }} focus:outline-none focus:ring-2 focus:ring-blue-400">
  <div class="flex items-start justify-between">
    <span class="text-[11px] font-medium text-blue-600 dark:text-blue-300 tracking-wide">اليوم</span>
    <span class="font-extrabold text-xl tabular-nums
                 {{ $isToday ? 'text-emerald-700 dark:text-emerald-300' : 'text-blue-700 dark:text-blue-200' }}">
      {{ $day }}
    </span>
  </div>

  <div class="mt-2 text-[13px] leading-5">
    <div class="text-slate-700 dark:text-slate-200">
      📦 طلبات: <strong class="tabular-nums">{{ $orders }}</strong>
    </div>
    <div class="text-emerald-700 dark:text-emerald-400">
      💰 إيراد: <strong class="tabular-nums">DH {{ number_format((float)$revenue,2) }}</strong>
    </div>
  </div>
</a>
