@extends('layouts.app')
@section('title','مادة جديدة')

@section('content')
<div class="mx-auto max-w-4xl">
  <!-- رأس الصفحة -->
  <div class="mb-4 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
    <div class="flex items-start justify-between gap-3">
      <div>
        <h1 class="text-lg font-semibold tracking-tight">إضافة مادة جديدة</h1>
        <p class="text-sm opacity-70">أدخل بيانات المادة بدقة لضمان تتبّع المخزون بشكل صحيح.</p>
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

  <!-- تنبيهات الأخطاء العامة (اختياري) -->
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

  <!-- البطاقة الرئيسية للنموذج -->
  <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
    <div class="border-b border-neutral-200 dark:border-neutral-800 px-4 py-3">
      <div class="flex items-center gap-2">
        <svg class="size-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M20 7H4m16 0-2 12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L4 7m4 0V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
        </svg>
        <span class="font-medium">بيانات المادة</span>
      </div>
    </div>

    <form action="{{ route('stock.items.store') }}" method="POST" class="p-4 space-y-4">
      @csrf

      {{-- يظل كل شيء داخل الفورم الجزئي كما هو --}}
      @include('stock.items.form')

      <!-- خط فاصل خفيف -->
      <div class="border-t border-dashed border-neutral-200 dark:border-neutral-800 my-2"></div>

      <!-- شريط الأوامر -->
      <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs opacity-60">
          تلميح: اضغط <kbd class="rounded border px-1">Ctrl</kbd> + <kbd class="rounded border px-1">Enter</kbd> للحفظ.
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
            حفظ
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- تذييل نصي صغير (اختياري) -->
  <div class="text-[11px] opacity-60 mt-2 ps-1">
    تُحفظ التغييرات في سجل الحركات عند التوريد أو التسوية لاحقًا.
  </div>
</div>
@endsection
