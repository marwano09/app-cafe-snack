@extends('layouts.app')
@section('title','تسوية المخزون')

@section('content')
<form action="{{ route('stock.adjustments.store') }}" method="POST" class="space-y-4">
  @csrf

  <div class="grid md:grid-cols-3 gap-4">
    <div class="md:col-span-2">
      <label class="block text-sm mb-1">المادة</label>
      <select name="stock_item_id" class="w-full input" required>
        <option value="">— اختر مادة —</option>
        @foreach($items as $si)
          <option value="{{ $si->id }}" @selected(old('stock_item_id')==$si->id)>
            {{ $si->name }} ({{ $si->unit }}) — متاح: {{ number_format($si->current_qty,2) }}
          </option>
        @endforeach
      </select>
      @error('stock_item_id')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="block text-sm mb-1">الكمية (قد تكون سالبة)</label>
      <input type="number" step="0.01" name="qty_change" value="{{ old('qty_change') }}" class="w-full input" required>
      @error('qty_change')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="md:col-span-3">
      <label class="block text-sm mb-1">سبب/ملاحظة</label>
      <input name="reason" value="{{ old('reason') }}" class="w-full input" placeholder="هدر / جرد / تصحيح …" required>
      @error('reason')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>
  </div>

  <button class="btn btn-primary mt-2">تسجيل التسوية</button>
</form>
@endsection
