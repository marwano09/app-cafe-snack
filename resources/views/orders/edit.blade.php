@extends('layouts.app')
@section('title',"تعديل الطلب #{$order->id}")

@section('content')
<form method="POST" action="{{ route('orders.update',$order) }}"
      class="max-w-xl rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 p-5">
  @csrf @method('PATCH')

  <div class="mb-4">
    <label class="block text-sm mb-1">الحالة</label>
    <select name="status" class="w-full rounded-lg bg-transparent border px-3 py-2">
      @foreach(['PENDING'=>'جديدة','PREPARING'=>'قيد التحضير','READY'=>'جاهزة','CANCELLED'=>'ملغاة'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('status',$order->status)===$k)>{{ $v }}</option>
      @endforeach
    </select>
    @error('status')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="mb-4">
    <label class="block text-sm mb-1">رقم الطاولة</label>
    <input type="number" name="table_number" min="1" class="w-full rounded-lg bg-transparent border px-3 py-2"
           value="{{ old('table_number',$order->table_number) }}">
    @error('table_number')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="mb-4">
    <label class="block text-sm mb-1">ملاحظات</label>
    <textarea name="notes" rows="3" class="w-full rounded-lg bg-transparent border px-3 py-2">{{ old('notes',$order->notes) }}</textarea>
    @error('notes')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="flex gap-3">
    <button class="px-4 py-2 rounded-lg bg-sky-600 text-white">حفظ</button>
    <a href="{{ route('orders.show',$order) }}" class="px-4 py-2 rounded-lg bg-neutral-200 dark:bg-neutral-800">إلغاء</a>
  </div>
</form>
@endsection
