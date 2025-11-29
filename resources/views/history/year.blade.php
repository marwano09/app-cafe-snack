@extends('layouts.app')
@section('title',"سنة $year")

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
  <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📊 ملخص السنة: {{ $year }}</h1>
  <div class="flex flex-wrap gap-2">
    <a class="rounded-full border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition" href="{{ route('history.year', $year-1) }}">← السنة السابقة</a>
    <a class="rounded-full border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition" href="{{ route('history.year', $year+1) }}">السنة التالية →</a>
    <a class="rounded-full border border-blue-500 text-blue-600 dark:text-blue-400 px-4 py-2 text-sm hover:bg-blue-50 dark:hover:bg-blue-900 transition" href="{{ route('history.month') }}">📅 الرجوع للشهر الحالي</a>
  </div>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
  @for($m=1; $m<=12; $m++)
    @php
      $stats = $byMonth[$m] ?? ['orders'=>0,'revenue'=>0];
      $title = \Carbon\Carbon::createFromDate($year, $m, 1)->locale('ar')->translatedFormat('F');
    @endphp
    <a href="{{ route('history.month.view', [$year, $m]) }}"
       class="group rounded-2xl border border-gray-200 dark:border-gray-700 p-5 bg-gradient-to-br from-white to-gray-50 dark:from-neutral-900 dark:to-neutral-800 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-[1.02]">
      <div class="font-semibold text-lg text-gray-700 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $title }}</div>
      <div class="text-sm mt-3 text-gray-600 dark:text-gray-300">
        عدد الطلبات: <strong class="text-gray-800 dark:text-white">{{ $stats['orders'] }}</strong>
      </div>
      <div class="text-sm text-gray-600 dark:text-gray-300">
        الإيراد: <strong class="text-green-600 dark:text-green-400">DH {{ number_format($stats['revenue'],2) }}</strong>
      </div>
    </a>
  @endfor
</div>
@endsection