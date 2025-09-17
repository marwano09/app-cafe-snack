@extends('layouts.app')
@section('title','فئة جديدة')

@section('content')
<h1 class="text-xl font-bold mb-4">إنشاء فئة</h1>

@if($errors->any())
  <div class="mb-3 rounded-xl border border-red-200 bg-red-50 text-red-900 p-3 text-sm">
    <ul class="list-disc pr-5 space-y-1">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" class="grid gap-4 max-w-lg">
  @csrf
  <div>
    <label class="block text-sm mb-1">الاسم</label>
    <input type="text" name="name" value="{{ old('name') }}" required
           class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900">
  </div>
<div>
  <label class="block text-sm mb-1">مكان التحضير</label>
  <select name="preparation_area" class="w-full input" required>
    <option value="kitchen" @selected(old('preparation_area','kitchen')==='kitchen')>المطبخ</option>
    <option value="bar"     @selected(old('preparation_area')==='bar')>البار</option>
  </select>
  @error('preparation_area')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
</div>

  <div>
    <label class="block text-sm mb-1">الصورة (اختياري)</label>
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.avif"
           class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900">
    <p class="text-xs opacity-60 mt-1">الحد الأقصى 2MB. الأنواع: JPG/PNG/WebP/AVIF.</p>
  </div>

  <div class="flex gap-2">
    <a href="{{ route('categories.index') }}" class="px-4 py-2 rounded-xl border">رجوع</a>
    <button class="px-4 py-2 rounded-xl bg-neutral-900 text-white">حفظ</button>
  </div>
</form>
@endsection
