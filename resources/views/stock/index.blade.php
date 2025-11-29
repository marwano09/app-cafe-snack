@extends('layouts.app')
@section('title','إدارة المخزون')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-bold">المخزون</h1>
  <form method="GET" class="flex gap-2">
    <input name="q" value="{{ request('q') }}" class="rounded-xl border px-3 py-2" placeholder="بحث…">
    <button class="rounded-xl border px-4 py-2">بحث</button>
  </form>
</div>

<div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
  @foreach($stocks as $s)
  <div class="rounded-2xl border p-4 bg-white/70 dark:bg-neutral-900/60">
    <div class="flex items-center justify-between">
      <div class="font-semibold">{{ $s->name }}</div>
      <div class="text-xs opacity-70">{{ $s->sku }}</div>
    </div>
    <div class="mt-2 text-sm">
      الكمية:
      <span class="font-bold {{ $s->isLow() ? 'text-red-600' : 'text-emerald-600' }}">
        {{ rtrim(rtrim(number_format($s->qty_on_hand,3,'.',''), '0'),'.') }} {{ $s->unit }}
      </span>
    </div>
    <div class="text-xs opacity-70">حد أدنى: {{ rtrim(rtrim(number_format($s->min_qty,3,'.',''), '0'),'.') }} {{ $s->unit }}</div>

    <form action="{{ route('stock.adjust',$s) }}" method="POST" class="mt-3 flex flex-wrap items-center gap-2">
      @csrf
      <select name="type" class="rounded-lg px-3 py-1.5">
        <option value="IN">إدخال</option>
        <option value="OUT">إخراج</option>
        <option value="ADJUST">تعديل</option>
      </select>
      <input type="number" step="0.001" min="0.001" name="qty" placeholder="الكمية" class="rounded-lg border px-3 py-1.5" required>
      <input type="text" name="reason" placeholder="سبب (اختياري)" class="rounded-lg border px-3 py-1.5 grow">
      <button class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5">حفظ</button>
    </form>
  </div>
  @endforeach
</div>

<div class="mt-4">{{ $stocks->links() }}</div>
@endsection
