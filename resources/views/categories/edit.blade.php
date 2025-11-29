@extends('layouts.app')
@section('title','تعديل الفئة')

@section('content')
<div class="mx-auto max-w-3xl p-4 sm:p-6" dir="rtl">
  <!-- عنوان الصفحة / مسار -->
  <div class="mb-4 flex items-center justify-between gap-3">
    <h1 class="text-2xl font-bold tracking-tight">تعديل الفئة: <span class="text-neutral-500 dark:text-neutral-400">{{ $category->name }}</span></h1>
    <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm font-medium shadow-sm transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:bg-neutral-800">
      رجوع
    </a>
  </div>

  <!-- كارت النموذج -->
  <div class="rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
    <div class="border-b border-neutral-100 p-4 dark:border-neutral-800">
      <p class="text-sm text-neutral-500 dark:text-neutral-400">حدّث بيانات الفئة بعناية. الحقول الإلزامية مميزة.</p>
    </div>

    <div class="p-4 sm:p-6">
      @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-900 dark:border-red-900/40 dark:bg-red-950/60 dark:text-red-200">
          <ul class="list-disc pr-5 space-y-1">
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('categories.update', $category) }}" enctype="multipart/form-data" class="grid gap-6">
        @csrf
        @method('PUT')

        <!-- مكان التحضير -->
        <div>
          <label class="mb-1 block text-sm font-medium text-neutral-700 dark:text-neutral-200">مكان التحضير <span class="text-red-500">*</span></label>
          <div class="relative">
            <select name="preparation_area" required
              class="w-full appearance-none rounded-2xl border border-neutral-200 bg-white px-3 py-2 pr-9 text-sm shadow-sm outline-none transition placeholder:text-neutral-400 focus:border-neutral-400 focus:ring-4 focus:ring-neutral-200/70 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-neutral-700 dark:focus:ring-neutral-800/60">
              <option value="kitchen" @selected(old('preparation_area', $category->preparation_area)==='kitchen')>المطبخ</option>
              <option value="bar" @selected(old('preparation_area', $category->preparation_area)==='bar')>البار</option>
            </select>
            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">▾</span>
          </div>
          @error('preparation_area')
            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
          @enderror
        </div>

        <!-- الاسم -->
        <div>
          <label class="mb-1 block text-sm font-medium text-neutral-700 dark:text-neutral-200">الاسم <span class="text-red-500">*</span></label>
          <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                 class="w-full rounded-2xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-neutral-400 focus:border-neutral-400 focus:ring-4 focus:ring-neutral-200/70 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-neutral-700 dark:focus:ring-neutral-800/60">
          @error('name')
            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
          @enderror
        </div>

        <!-- الصورة الحالية & التحكم -->
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="mb-1 block text-sm font-medium text-neutral-700 dark:text-neutral-200">الصورة الحالية</label>
            <div class="group overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 shadow-sm dark:border-neutral-800 dark:bg-neutral-950/40">
              @if($category->image_url)
                <img src="{{ $category->image_url }}" alt="" class="h-44 w-full object-cover transition duration-300 group-hover:scale-[1.02]">
              @else
                <div class="grid h-44 place-items-center text-sm text-neutral-500 dark:text-neutral-400">لا توجد صورة</div>
              @endif
            </div>
            <label class="mt-3 inline-flex cursor-pointer select-none items-center gap-2 text-sm text-neutral-700 dark:text-neutral-200">
              <input type="checkbox" name="remove_image" value="1" id="removeImage"
                     class="size-4 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:focus:ring-neutral-700">
              إزالة الصورة
            </label>
          </div>

          <div>
            <label class="mb-1 block text-sm font-medium text-neutral-700 dark:text-neutral-200">تحديث الصورة (اختياري)</label>
            <div class="rounded-2xl border border-dashed border-neutral-300 p-3 text-sm dark:border-neutral-700">
              <input type="file" name="image" id="imageInput" accept=".jpg,.jpeg,.png,.webp,.avif"
                     class="block w-full cursor-pointer rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition file:mr-3 file:rounded-lg file:border-0 file:bg-neutral-100 file:px-3 file:py-2 file:text-neutral-700 hover:file:bg-neutral-200 focus:border-neutral-400 focus:ring-4 focus:ring-neutral-200/70 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100 dark:file:bg-neutral-800 dark:file:text-neutral-200 dark:hover:file:bg-neutral-700 dark:focus:border-neutral-700 dark:focus:ring-neutral-800/60">
              <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">الحد الأقصى المقترح: 2MB — الصيغ المدعومة: JPG, PNG, WEBP, AVIF.</p>
              <div id="previewWrap" class="mt-3 hidden">
                <div class="text-xs mb-1 text-neutral-500 dark:text-neutral-400">معاينة الصورة الجديدة</div>
                <img id="previewImg" class="h-44 w-full rounded-xl object-cover" alt="preview">
              </div>
            </div>
          </div>
        </div>

        <!-- الأزرار -->
        <div class="mt-2 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
          <a href="{{ route('categories.index') }}" class="inline-flex items-center justify-center rounded-xl border border-neutral-200 bg-white px-4 py-2 text-sm font-medium shadow-sm transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100 dark:hover:bg-neutral-800">إلغاء</a>
          <button class="inline-flex items-center justify-center rounded-xl bg-neutral-900 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-black/90 focus:outline-none focus:ring-4 focus:ring-neutral-300 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-white">
            تحديث
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- تحسينات أمامية بسيطة: معاينة الصورة وتفعيل/تعطيل -->
<script>
  (function(){
    const input = document.getElementById('imageInput');
    const wrap  = document.getElementById('previewWrap');
    const img   = document.getElementById('previewImg');
    const remove = document.getElementById('removeImage');

    if(input){
      input.addEventListener('change', function(){
        const file = this.files && this.files[0];
        if(!file) { wrap?.classList.add('hidden'); return; }
        const reader = new FileReader();
        reader.onload = e => {
          if(img && wrap){ img.src = e.target.result; wrap.classList.remove('hidden'); }
          if(remove) remove.checked = false; // لو اختار صورة جديدة، نفك خيار الإزالة
        };
        reader.readAsDataURL(file);
      });
    }

    if(remove && input){
      remove.addEventListener('change', function(){
        if(this.checked){
          input.value = '';
          const event = new Event('change');
          input.dispatchEvent(event);
        }
      });
    }
  })();
</script>
@endsection
