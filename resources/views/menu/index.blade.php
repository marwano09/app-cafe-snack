@extends('layouts.app')
@section('title','القائمة')

@section('content')
<div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4 bg-white dark:bg-neutral-900">
  <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
    <form method="GET" class="flex flex-wrap items-center gap-2">
      <input type="text" name="q" value="{{ $q }}" placeholder="ابحث عن صنف..."
             class="rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2">
      <select name="category_id"
              class="rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2">
        <option value="">كل الفئات</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" {{ (string)$category_id === (string)$c->id ? 'selected':'' }}>
            {{ $c->name }}
          </option>
        @endforeach
      </select>
      <button class="rounded-xl border px-4 py-2">تصفية</button>
    </form>

    <a href="{{ route('menu.create') }}" class="rounded-xl bg-black text-white px-4 py-2">صنف جديد</a>
  </div>
</div>

<div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3 mt-4">
  @forelse($items as $it)
    <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4 bg-white dark:bg-neutral-900">
      <div class="flex items-start gap-3">
        <img src="{{ $it->image_url }}" alt="{{ $it->name }}" class="w-20 h-20 object-cover rounded-lg ring-1 ring-black/10">
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
              <div class="font-semibold truncate">{{ $it->name }}</div>
              <div class="text-xs opacity-70 mt-1 truncate">الفئة: {{ optional($it->category)->name ?? '—' }}</div>
            </div>
            <div class="text-sm font-bold shrink-0">DH {{ number_format($it->price,2) }}</div>
          </div>

          @if($it->description)
            <div class="text-sm opacity-80 mt-2 line-clamp-2">{{ $it->description }}</div>
          @endif

          <div class="flex items-center justify-between mt-3">
            <span class="text-xs {{ $it->is_available ? 'text-emerald-600' : 'text-red-600' }}">
              {{ $it->is_available ? 'متاح' : 'غير متاح' }}
            </span>
            <div class="flex items-center gap-2">
              <a class="text-xs underline" href="{{ route('menu.edit',$it) }}">تعديل</a>
              <form method="POST" action="{{ route('menu.destroy',$it) }}" onsubmit="return confirm('حذف الصنف؟')">
                @csrf @method('DELETE')
                <button class="text-xs text-red-600 underline">حذف</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="opacity-70">لا توجد أصناف.</div>
  @endforelse
</div>

<div class="mt-4">{{ $items->links() }}</div>
@endsection
