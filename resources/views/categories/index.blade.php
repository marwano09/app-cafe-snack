@extends('layouts.app')
@section('title','الفئات')

@section('content')
<div class="mx-auto max-w-6xl p-4 sm:p-6" dir="rtl">
  <!-- شريط علوي: عنوان + إجراء أساسي -->
  <div class="mb-6 flex items-center justify-between gap-3">
    <div>
      <h1 class="text-2xl font-bold tracking-tight">الفئات</h1>
      <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">إدارة وعرض الفئات المتاحة في النظام.</p>
    </div>

    <a href="{{ route('categories.create') }}"
       class="inline-flex items-center gap-2 rounded-xl border border-neutral-200 bg-white px-4 py-2 text-sm font-medium shadow-sm transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:bg-neutral-800">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16M20 12H4" />
      </svg>
      فئة جديدة
    </a>
  </div>

  <!-- شريط أدوات بسيط (اختياري بصريًا فقط) -->
  <div class="mb-6 rounded-2xl border border-neutral-200 bg-white p-3 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="text-sm text-neutral-600 dark:text-neutral-300">
        المجموع: <span class="font-semibold">{{ $categories->count() }}</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="hidden sm:block text-xs text-neutral-500 dark:text-neutral-400"></div>
      </div>
    </div>
  </div>

  <!-- الشبكة -->
  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($categories as $c)
      <div class="group rounded-2xl border border-neutral-200 bg-white shadow-sm transition-all duration-300 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
        <!-- صورة / غلاف -->
        <div class="relative overflow-hidden rounded-t-2xl">
          @php
            $hasImage = !empty($c->image_path);
          @endphp

          @if($hasImage)
            <img src="{{ $c->image_url }}" alt="{{ $c->name }}" loading="lazy"
                 class="h-44 w-full object-cover transition duration-500 group-hover:scale-[1.02]"/>
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/20 via-black/0 to-transparent"></div>
          @else
            <div class="h-44 w-full bg-neutral-100 dark:bg-neutral-800"></div>
            <div class="absolute inset-0 grid place-items-center text-sm text-neutral-500 dark:text-neutral-400">
              <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="3" width="18" height="18" rx="2"/>
                </svg>
                بدون صورة
              </div>
            </div>
          @endif

          <!-- بادج مكان التحضير أعلى الصورة -->
          <div class="absolute left-3 top-3">
            <span class="rounded-full border px-2 py-0.5 text-[11px] font-medium
              {{ $c->preparation_area==='bar'
                ? 'border-amber-200 bg-amber-100 text-amber-800 dark:border-amber-800/60 dark:bg-amber-900 dark:text-amber-200'
                : 'border-emerald-200 bg-emerald-100 text-emerald-800 dark:border-emerald-800/60 dark:bg-emerald-900 dark:text-emerald-200' }}">
              {{ $c->preparation_area==='bar' ? 'البار' : 'المطبخ' }}
            </span>
          </div>
        </div>

        <!-- المحتوى -->
        <div class="p-4">
          <div class="flex items-start justify-between gap-3">
            <div>
              <div class="line-clamp-1 text-lg font-semibold tracking-tight">{{ $c->name }}</div>
              <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                slug: <span class="font-mono">{{ $c->slug }}</span>
              </div>
            </div>

            <!-- أزرار سريعة علوية -->
            <div class="flex items-center gap-2">
              <a href="{{ route('categories.edit',$c) }}"
                 class="inline-flex h-9 items-center justify-center rounded-lg border border-neutral-200 bg-white px-2.5 text-xs font-medium shadow-sm transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:bg-neutral-800"
                 title="تعديل">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                </svg>
              </a>

              <form action="{{ route('categories.destroy',$c) }}" method="POST" onsubmit="return confirm('حذف الفئة؟');">
                @csrf @method('DELETE')
                <button class="inline-flex h-9 items-center justify-center rounded-lg border border-rose-200 bg-white px-2.5 text-xs font-medium text-rose-600 shadow-sm transition hover:bg-rose-50 dark:border-rose-900/40 dark:bg-neutral-900 dark:hover:bg-rose-900/20"
                        title="حذف">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </form>
            </div>
          </div>

          <!-- أزرار سفلية موسّعة -->
          <div class="mt-4 flex gap-2">
            <a href="{{ route('categories.edit',$c) }}"
               class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm font-medium shadow-sm transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100 dark:hover:bg-neutral-800">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
              </svg>
              تعديل
            </a>

            <form action="{{ route('categories.destroy',$c) }}" method="POST" onsubmit="return confirm('حذف الفئة؟');" class="flex-1">
              @csrf @method('DELETE')
              <button class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-3 py-2 text-sm font-medium text-rose-600 shadow-sm transition hover:bg-rose-50 dark:border-rose-900/40 dark:bg-neutral-900 dark:hover:bg-rose-900/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                حذف
              </button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-full">
        <div class="rounded-2xl border border-neutral-200 bg-white p-8 text-center shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
          <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-neutral-100 text-neutral-400 dark:bg-neutral-800">—</div>
          <p class="text-neutral-600 dark:text-neutral-300">لا توجد فئات حالياً.</p>
          <a href="{{ route('categories.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm font-medium shadow-sm transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:bg-neutral-800">
            إنشاء أول فئة
          </a>
        </div>
      </div>
    @endforelse
  </div>
</div>
@endsection
