@extends('layouts.app')
@section('title','تعديل مادة')

@section('content')
<div class="mx-auto max-w-4xl">
  <!-- رأس الصفحة -->
  <div class="mb-4 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
    <div class="flex items-start justify-between gap-3">
      <div>
        <h1 class="text-lg font-semibold tracking-tight">تعديل المادة</h1>
        <p class="text-sm opacity-70">قم بتحديث بيانات المادة وحفظ التغييرات.</p>
      </div>
      <a href="{{ route('stock.items.index') }}"
         class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 px-3 py-2 text-sm hover:bg-neutral-50 dark:hover:bg-neutral-800 transition"
         aria-label="عودة إلى المخزون">
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
        </svg>
        عودة
      </a>
    </div>
  </div>

  <!-- الأخطاء -->
  @if ($errors->any())
    <div class="mb-4 rounded-xl border border-rose-300 dark:border-rose-800 bg-rose-50 dark:bg-rose-950/30 p-3 text-sm text-rose-700 dark:text-rose-200">
      <div class="font-medium mb-1">تحقّق من الحقول التالية:</div>
      <ul class="list-disc ms-5 space-y-0.5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- النموذج -->
  <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
    <div class="border-b border-neutral-200 dark:border-neutral-800 px-4 py-3">
      <div class="flex items-center gap-2">
        <svg class="size-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 20h9M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"/>
        </svg>
        <span class="font-medium">تحديث بيانات المادة</span>
      </div>
    </div>

    <form action="{{ route('stock.items.update', $item) }}" method="POST" class="p-4 space-y-4">
      @csrf
      @method('PUT')

      {{-- نموذج الحقول --}}
      @include('stock.items.form', ['item'=>$item])

      <div class="border-t border-dashed border-neutral-200 dark:border-neutral-800 my-2"></div>

      <!-- شريط الأوامر -->
      <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs opacity-60">
          تلميح: يمكنك الحفظ بالضغط على <kbd class="rounded border px-1">Ctrl</kbd> + <kbd class="rounded border px-1">Enter</kbd>.
        </p>
        <div class="flex items-center gap-2">
          <a href="{{ route('stock.items.index') }}"
             class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 px-4 py-2 text-sm hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
            إلغاء
          </a>
          <button type="submit"
                  class="inline-flex items-center gap-2 rounded-xl bg-black text-white px-5 py-2 text-sm hover:opacity-90 active:opacity-80 transition">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 12l4 4L19 6"/>
            </svg>
            تحديث
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
