@extends('layouts.app')
@section('title','توريد/شراء')

@section('content')
<form action="{{ route('stock.purchases.store') }}" method="POST" class="space-y-4">
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
      <label class="block text-sm mb-1">الكمية (+)</label>
      <input type="number" step="0.01" name="qty" value="{{ old('qty') }}" class="w-full input" required>
      @error('qty')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="block text-sm mb-1">السعر الإجمالي (اختياري)</label>
      <input type="number" step="0.01" name="cost_total" value="{{ old('cost_total') }}" class="w-full input">
      @error('cost_total')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="md:col-span-3">
      <label class="block text-sm mb-1">ملاحظة</label>
      <input name="note" value="{{ old('note') }}" class="w-full input" placeholder="فاتورة #123 / المورد: …">
      @error('note')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>
  </div>

  <button class="btn btn-primary mt-2">تسجيل التوريد</button>
</form>
@endsection
