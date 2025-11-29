@extends('layouts.app')
@section('title','القائمة')

@section('content')
@php
  // fallbacks from request if not passed by controller
  $q = $q ?? request('q');
  $category_id = $category_id ?? request('category_id');
  $availability = request('availability');
  $sort = request('sort');
@endphp

{{-- Header --}}
<div class="mb-4 sm:mb-6">
  <div class="flex items-center justify-between gap-3">
    <div>
      <h1 class="text-xl sm:text-2xl font-semibold tracking-tight text-neutral-900 dark:text-neutral-100">القائمة</h1>
      <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">إدارة الأصناف، التصفية، والبحث بسرعة.</p>
    </div>
    <a href="{{ route('menu.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-neutral-900 text-white px-4 py-2 text-sm font-medium hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2h6Z"/></svg>
      صنف جديد
    </a>
  </div>
</div>

{{-- Filter Bar --}}
<div class="sticky top-2 z-10">
  <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4 sm:p-5 bg-white/90 dark:bg-neutral-900/90 backdrop-blur shadow-sm">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
      <div>
        <label for="q" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">بحث</label>
        <div class="relative">
          <input id="q" type="text" name="q" value="{{ $q }}" placeholder="ابحث عن صنف..."
                 class="peer rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 ps-10 pe-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                 aria-label="ابحث عن صنف">
          <svg class="absolute start-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 peer-focus:text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="m21 20.3-4.7-4.7a7.5 7.5 0 1 0-1.4 1.4l4.7 4.7 1.4-1.4ZM4.5 10a5.5 5.5 0 1 1 11 0 5.5 5.5 0 0 1-11 0Z"/></svg>
        </div>
      </div>

      <div>
        <label for="category_id" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">الفئة</label>
        <select id="category_id" name="category_id"
                class="rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
          <option value="">كل الفئات</option>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ (string)$category_id === (string)$c->id ? 'selected':'' }}>
              {{ $c->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label for="availability" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">التوفر</label>
        <select id="availability" name="availability"
                class="rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
          <option value="">الكل</option>
          <option value="1" {{ $availability === '1' ? 'selected':'' }}>متاح</option>
          <option value="0" {{ $availability === '0' ? 'selected':'' }}>غير متاح</option>
        </select>
      </div>

      <div>
        <label for="sort" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">الترتيب</label>
        <select id="sort" name="sort"
                class="rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
          <option value="">افتراضي</option>
          <option value="new"        {{ $sort==='new'?'selected':'' }}>الأحدث</option>
          <option value="name"       {{ $sort==='name'?'selected':'' }}>الاسم (أ→ي)</option>
          <option value="price_asc"  {{ $sort==='price_asc'?'selected':'' }}>السعر (تصاعدي)</option>
          <option value="price_desc" {{ $sort==='price_desc'?'selected':'' }}>السعر (تنازلي)</option>
        </select>
      </div>

      <div class="flex gap-2">
        <button class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 px-4 py-2 text-sm font-medium hover:bg-neutral-100 dark:hover:bg-neutral-800 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor"><path d="M3 5h18v2H3V5Zm0 6h12v2H3v-2Zm0 6h6v2H3v-2Z"/></svg>
          تطبيق التصفية
        </button>
        @if(request()->hasAny(['q','category_id','availability','sort']))
          <a href="{{ route('menu.index') }}"
             class="inline-flex items-center justify-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 px-4 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
            إعادة ضبط
          </a>
        @endif
      </div>
    </form>

    {{-- quick stats --}}
    <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3">
      <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-3">
        <div class="text-xs text-neutral-500">إجمالي النتائج</div>
        <div class="mt-1 font-semibold">{{ number_format($items->total()) }}</div>
      </div>
      <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-3">
        <div class="text-xs text-neutral-500">معروضة الآن</div>
        <div class="mt-1 font-semibold">{{ $items->count() }}</div>
      </div>
      <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-3">
        <div class="text-xs text-neutral-500">الفئة</div>
        <div class="mt-1 font-semibold truncate max-w-[12rem]">
          {{ $category_id ? optional($categories->firstWhere('id',$category_id))->name : '—' }}
        </div>
      </div>
      <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-3">
        <div class="text-xs text-neutral-500">التوفر</div>
        <div class="mt-1 font-semibold">
          {{ $availability===''||$availability===null?'الكل':($availability==='1'?'متاح':'غير متاح') }}
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Cards Grid --}}
<div class="grid gap-5 sm:gap-6 md:grid-cols-2 xl:grid-cols-3 mt-6">
  @forelse($items as $it)
    <article class="group rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-5 shadow-sm hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-800 transition-all duration-300 focus-within:ring-2 focus-within:ring-blue-500">
      <div class="flex items-start gap-4">
        <div class="relative shrink-0">
          <img
            src="{{ $it->image_url ?: asset('images/placeholder.webp') }}"
            alt="{{ $it->name }}"
            class="w-24 h-24 sm:w-28 sm:h-28 object-cover rounded-xl ring-1 ring-black/5 dark:ring-white/10"
            onerror="this.src='{{ asset('images/placeholder.webp') }}'">
          <span class="absolute -end-2 -top-2 inline-flex items-center rounded-full text-[10px] px-2 py-0.5 border
                       {{ $it->is_available
                           ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                           : 'bg-rose-50 text-rose-700 border-rose-200' }}">
            {{ $it->is_available ? 'متاح' : 'غير متاح' }}
          </span>
        </div>

        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
              <h3 class="font-semibold text-base leading-6 text-neutral-900 dark:text-neutral-100 truncate">
                {{ $it->name }}
              </h3>
              <div class="mt-1 flex flex-wrap items-center gap-1.5">
                <span class="inline-flex items-center rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 px-2 py-0.5 text-[11px] border border-neutral-200 dark:border-neutral-700">
                  {{ optional($it->category)->name ?? 'غير مصنّف' }}
                </span>
                @if(!empty($it->sku))
                  <span class="inline-flex items-center rounded-lg bg-blue-50 text-blue-700 px-2 py-0.5 text-[11px] border border-blue-200">
                    SKU: {{ $it->sku }}
                  </span>
                @endif
              </div>
            </div>
            <div class="text-sm sm:text-base font-bold text-green-700 dark:text-green-400 shrink-0">
              DH {{ number_format($it->price,2) }}
            </div>
          </div>

          @if($it->description)
            <p class="text-[13px] text-neutral-600 dark:text-neutral-300 mt-2 line-clamp-2">
              {{ $it->description }}
            </p>
          @endif

          <div class="mt-4 flex items-center justify-between">
            <span class="text-[11px] text-neutral-500 inline-flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm0-6a2 2 0 0 1 2 2v1.06a8 8 0 0 1 6.94 6.94H22a2 2 0 0 1 0 4h-1.06A8 8 0 0 1 14 18.94V20a2 2 0 1 1-4 0v-1.06A8 8 0 0 1 4.06 12H3a2 2 0 1 1 0-4h1.06A8 8 0 0 1 10 5.06V4a2 2 0 0 1 2-2Z"/></svg>
              آخر تحديث: {{ optional($it->updated_at)->diffForHumans() }}
            </span>

            {{-- Actions via native details/summary for accessibility (no JS) --}}
            <details class="relative">
              <summary class="list-none cursor-pointer inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg border border-neutral-300 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
                إجراءات
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5H7z"/></svg>
              </summary>
              <div class="absolute end-0 mt-2 w-40 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-lg p-1 z-10">
                <a href="{{ route('menu.edit',$it) }}"
                   class="flex items-center gap-2 px-3 py-2 text-xs rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Zm14.71-11.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0L12.13 4.12l3.75 3.75 1.83-1.83Z"/></svg>
                  تعديل
                </a>
                <form method="POST" action="{{ route('menu.destroy',$it) }}" onsubmit="return confirm('هل تريد حذف هذا الصنف نهائياً؟')">
                  @csrf @method('DELETE')
                  <button type="submit"
                          class="w-full text-start flex items-center gap-2 px-3 py-2 text-xs rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950 text-rose-700 dark:text-rose-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 7h12v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V7Zm3-3h6l1 1h3v2H5V5h3l1-1Z"/></svg>
                    حذف
                  </button>
                </form>
              </div>
            </details>
          </div>

          {{-- Optional stock bar (bind when available) --}}
          @if(isset($it->stock_percent))
            <div class="mt-4">
              <div class="flex items-center justify-between text-[11px] text-neutral-500 mb-1">
                <span>المخزون</span><span>{{ (int)$it->stock_percent }}%</span>
              </div>
              <div class="h-2 rounded-full bg-neutral-100 dark:bg-neutral-800 overflow-hidden">
                <div class="h-full bg-emerald-500" style="width: {{ (int)$it->stock_percent }}%"></div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </article>
  @empty
    <div class="col-span-full">
      <div class="rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-700 p-10 text-center bg-white dark:bg-neutral-900">
        <div class="mx-auto w-12 h-12 grid place-items-center rounded-full bg-neutral-100 dark:bg-neutral-800 mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-neutral-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 7a5 5 0 1 1 0 10A5 5 0 0 1 12 7Zm0-5C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2Z"/></svg>
        </div>
        <h3 class="font-semibold text-neutral-800 dark:text-neutral-200">لا توجد نتائج مطابقة</h3>
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">عدّل معايير البحث أو أضف صنفاً جديداً.</p>
        <div class="mt-4">
          <a href="{{ route('menu.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-neutral-900 text-white px-4 py-2 text-sm font-medium hover:bg-neutral-800 transition">
            إضافة أول صنف
          </a>
        </div>
      </div>
    </div>
  @endforelse
</div>

{{-- Pagination --}}
@if($items->hasPages())
  <div class="mt-6">
    {{ $items->appends(request()->query())->links() }}
  </div>
@endif
@endsection
