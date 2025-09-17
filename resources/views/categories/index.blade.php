@extends('layouts.app')
@section('title','الفئات')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-bold">الفئات</h1>
  <a href="{{ route('categories.create') }}"
     class="rounded-xl bg-neutral-900 text-white px-4 py-2 hover:opacity-90">+ فئة جديدة</a>
</div>

@if(session('ok'))
  <div class="mb-3 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-900 p-3 text-sm">
    {{ session('ok') }}
  </div>
@endif

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
  @forelse($categories as $cat)
    <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 overflow-hidden">
      <div class="aspect-[3/2] bg-neutral-100 dark:bg-neutral-800">
        @if($cat->image_url)
          <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-cover">
        @else
          <div class="w-full h-full grid place-items-center text-sm opacity-60">بدون صورة</div>
        @endif
      </div>
      <div class="p-4">
        <div class="font-semibold">{{ $cat->name }}</div>
        <div class="text-xs opacity-60 mt-1">السلَغ: {{ $cat->slug }}</div>

        <div class="mt-3 flex gap-2">
          <a href="{{ route('categories.edit', $cat) }}"
             class="px-3 py-1.5 rounded-lg border hover:bg-neutral-50 dark:hover:bg-neutral-800">تعديل</a>
          <form method="POST" action="{{ route('categories.destroy', $cat) }}"
                onsubmit="return confirm('حذف الفئة؟')">
            @csrf @method('DELETE')
            <button class="px-3 py-1.5 rounded-lg border border-red-300 text-red-700 hover:bg-red-50">حذف</button>
          </form>
        </div>
      </div>
    </div>
  @empty
    <div class="col-span-full text-center opacity-60">لا توجد فئات بعد.</div>
  @endforelse
</div>

<div class="mt-6">{{ $categories->links() }}</div>
@endsection
