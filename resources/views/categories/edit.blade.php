@extends('layouts.app')
@section('title','تعديل الفئة')

@section('content')
<h1 class="text-xl font-bold mb-4">تعديل الفئة: {{ $category->name }}</h1>

@if($errors->any())
  <div class="mb-3 rounded-xl border border-red-200 bg-red-50 text-red-900 p-3 text-sm">
    <ul class="list-disc pr-5 space-y-1">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('categories.update', $category) }}" enctype="multipart/form-data" class="grid gap-4 max-w-lg">
  @csrf @method('PUT')
<div>
  <label class="block text-sm mb-1">مكان التحضير</label>
  <select name="preparation_area" class="w-full input" required>
    <option value="kitchen" @selected(old('preparation_area', $category->preparation_area)==='kitchen')>المطبخ</option>
    <option value="bar"     @selected(old('preparation_area', $category->preparation_area)==='bar')>البار</option>
  </select>
  @error('preparation_area')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
</div>

  <div>
    <label class="block text-sm mb-1">الاسم</label>
    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
           class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900">
  </div>

  <div>
    <label class="block text-sm mb-1">الصورة الحالية</label>
    <div class="rounded-xl border overflow-hidden max-w-xs">
      @if($category->image_url)
        <img src="{{ $category->image_url }}" class="w-full h-40 object-cover" alt="">
      @else
        <div class="h-40 grid place-items-center text-sm opacity-60">لا توجد صورة</div>
      @endif
    </div>

    <label class="inline-flex items-center gap-2 mt-2 text-sm">
      <input type="checkbox" name="remove_image" value="1" class="rounded border">
      إزالة الصورة
    </label>
  </div>

  <div>
    <label class="block text-sm mb-1">تحديث الصورة (اختياري)</label>
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.avif"
           class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900">
  </div>

  <div class="flex gap-2">
    <a href="{{ route('categories.index') }}" class="px-4 py-2 rounded-xl border">رجوع</a>
    <button class="px-4 py-2 rounded-xl bg-neutral-900 text-white">تحديث</button>
  </div>
</form>
@endsection
