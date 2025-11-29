@extends('layouts.app')
@section('title','تعديل صنف')

@section('content')
@php
  $backUrl = url()->previous() ?: route('menu.index');
@endphp

<div class="max-w-6xl mx-auto">
  {{-- Breadcrumbs --}}
  <nav class="mb-4 text-sm text-neutral-500 dark:text-neutral-400" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2">
      <li><a href="{{ route('menu.index') }}" class="hover:underline">القائمة</a></li>
      <li class="opacity-60">/</li>
      <li class="text-neutral-900 dark:text-neutral-200 font-medium">تعديل صنف</li>
    </ol>
  </nav>

  {{-- Header + Actions --}}
  <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <div>
      <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-neutral-900 dark:text-neutral-100">تعديل صنف</h1>
      <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">حدّث المعلومات، بدّل الصورة، وأدر التوفّر بكل سلاسة.</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ $backUrl }}"
         class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M10 19 1 12l9-7v4h13v6H10v4Z"/></svg>
        رجوع
      </a>
      <a href="{{ route('menu.edit',$item) }}"
         class="hidden sm:inline-flex items-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
        معاينة الصفحة
      </a>
      <form method="POST" action="{{ route('menu.destroy',$item) }}" onsubmit="return confirm('هل تريد حذف هذا الصنف نهائيًا؟')">
        @csrf @method('DELETE')
        <button type="submit"
          class="inline-flex items-center gap-2 rounded-xl border border-rose-200 text-rose-700 dark:text-rose-300 dark:border-rose-800 px-3 py-2 text-sm hover:bg-rose-50 dark:hover:bg-rose-950 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 7h12v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V7Zm3-3h6l1 1h3v2H5V5h3l1-1Z"/></svg>
          حذف
        </button>
      </form>
    </div>
  </header>

  {{-- Alerts (server-side) --}}
  @if(session('status'))
    <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">{{ session('status') }}</div>
  @endif

  <form id="editForm" action="{{ route('menu.update',$item) }}" method="post" enctype="multipart/form-data" class="space-y-6">
    @csrf @method('PUT')

    <div class="grid lg:grid-cols-3 gap-6">
      {{-- Main form --}}
      <section class="lg:col-span-2 space-y-6">
        {{-- Section: Basics --}}
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-5 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-neutral-900 dark:text-neutral-100">البيانات الأساسية</h2>
            <span class="inline-flex items-center gap-1 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 px-2 py-1 text-[11px]">
              معرّف: <span class="font-mono">{{ $item->id }}</span>
            </span>
          </div>

          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label for="name" class="flex items-center justify-between mb-1">
                <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400">الاسم</span>
                <span id="nameCount" class="text-[11px] text-neutral-400">0/120</span>
              </label>
              <input id="name" name="name" maxlength="120" value="{{ old('name',$item->name) }}" required
                     class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                     aria-describedby="nameHelp">
              <div id="nameHelp" class="text-[11px] text-neutral-500 mt-1">اكتب اسمًا واضحًا وقصيرًا.</div>
              @error('name')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
              <label for="price" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">السعر (DH)</label>
              <input id="price" inputmode="decimal" type="number" step="0.01" name="price" value="{{ old('price',$item->price) }}" required
                     class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
              <div class="text-[11px] text-neutral-500 mt-1">يُعرض للزبون بالدرهم (يشمل الضرائب إن وُجدت).</div>
              @error('price')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
              <label for="category_id" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">الفئة</label>
              <select id="category_id" name="category_id" required
                      class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                @foreach($categories as $c)
                  <option value="{{ $c->id }}" @selected(old('category_id',$item->category_id)==$c->id)>{{ $c->name }}</option>
                @endforeach
              </select>
              @error('category_id')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
              <span class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">التوفّر</span>
              <label class="inline-flex items-center gap-3 select-none">
                <input type="hidden" name="is_available" value="0">
                <input type="checkbox" name="is_available" value="1" @checked(old('is_available',$item->is_available)=='1') class="sr-only peer">
                <span class="w-11 h-6 rounded-full bg-neutral-300 dark:bg-neutral-700 relative transition peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-0.5 after:start-0.5 after:w-5 after:h-5 after:bg-white after:rounded-full after:transition peer-checked:after:translate-x-5"></span>
                <span class="text-sm text-neutral-700 dark:text-neutral-300" id="availabilityLabel">
                  {{ old('is_available',$item->is_available)=='1' ? 'متاح' : 'غير متاح' }}
                </span>
              </label>
              @error('is_available')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="md:col-span-2">
              <label for="description" class="flex items-center justify-between mb-1">
                <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400">الوصف</span>
                <span id="descCount" class="text-[11px] text-neutral-400">0/400</span>
              </label>
              <textarea id="description" name="description" rows="4" maxlength="400"
                        class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                        placeholder="مكوّنات، ملاحظات تقديم، حساسية...">{{ old('description',$item->description) }}</textarea>
              @error('description')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>

            {{-- Optional: SKU & Tags (ready if you use them) --}}
            <div>
              <label for="sku" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">SKU (اختياري)</label>
              <input id="sku" name="sku" value="{{ old('sku',$item->sku ?? '') }}"
                     class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
              @error('sku')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label for="tags" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">وسوم (اختياري)</label>
              <input id="tags" name="tags" value="{{ old('tags',$item->tags ?? '') }}" placeholder="حار, نباتي, خالي من الغلوتين..."
                     class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
              @error('tags')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        {{-- Section: Media --}}
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-5 shadow-sm">
          <h2 class="text-base font-semibold text-neutral-900 dark:text-neutral-100 mb-4">الوسائط</h2>

          <div class="flex items-start gap-4">
            {{-- Current image --}}
            <div class="shrink-0">
              <img id="currentImage"
                   src="{{ $item->image_url ?: asset('images/placeholder.webp') }}"
                   alt="الصورة الحالية"
                   class="h-28 w-28 object-cover rounded-xl ring-1 ring-black/5 dark:ring-white/10"
                   onerror="this.src='{{ asset('images/placeholder.webp') }}'">
              <label class="mt-2 inline-flex items-center gap-2 text-xs">
                <input type="checkbox" name="remove_image" value="1" class="rounded border-neutral-300 dark:border-neutral-700">
                <span>حذف الصورة الحالية</span>
              </label>
            </div>

            {{-- Dropzone --}}
            <div class="flex-1">
              <label for="imageInput"
                     class="group grid place-items-center text-center cursor-pointer rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-700 p-6 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto w-8 h-8 text-neutral-400 group-hover:text-neutral-600 dark:group-hover:text-neutral-200" viewBox="0 0 24 24" fill="currentColor"><path d="M19 15v4a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-4H3l9-9 9 9h-2ZM5 17v2h14v-2H5Z"/></svg>
                  <div class="mt-2 text-sm">اسحب الصورة هنا أو انقر لاختيار ملف</div>
                  <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">JPEG, PNG, WEBP — حتى 2MB</div>
                </div>
                <input id="imageInput" type="file" name="image" accept="image/*" class="hidden">
              </label>
              @error('image')<div class="text-rose-600 text-xs mt-2">{{ $message }}</div>@enderror

              {{-- New preview --}}
              <div id="previewWrap" class="hidden mt-4">
                <div class="text-xs text-neutral-500 mb-2">معاينة الصورة الجديدة:</div>
                <img id="previewImage" class="h-28 w-28 object-cover rounded-xl ring-1 ring-black/5 dark:ring-white/10" alt="">
              </div>
            </div>
          </div>
        </div>
      </section>

      {{-- Sidebar: Live Preview + Info --}}
      <aside class="space-y-6">
        {{-- Live card preview --}}
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-5 shadow-sm">
          <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100 mb-3">معاينة البطاقة</h3>
          <article class="group rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4">
            <div class="flex items-start gap-3">
              <div class="relative">
                <img id="liveCardImage" src="{{ $item->image_url ?: asset('images/placeholder.webp') }}" class="w-20 h-20 object-cover rounded-xl ring-1 ring-black/5 dark:ring-white/10" alt="">
                <span id="liveAvail" class="absolute -end-2 -top-2 inline-flex items-center rounded-full text-[10px] px-2 py-0.5 border {{ $item->is_available ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                  {{ $item->is_available ? 'متاح' : 'غير متاح' }}
                </span>
              </div>
              <div class="min-w-0 flex-1">
                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0">
                    <div id="liveName" class="font-semibold text-base truncate">{{ $item->name }}</div>
                    <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1 truncate" id="liveCategory">
                      الفئة: {{ optional($item->category)->name ?? '—' }}
                    </div>
                  </div>
                  <div id="livePrice" class="text-sm font-bold text-green-700 dark:text-green-400 shrink-0">
                    DH {{ number_format($item->price,2) }}
                  </div>
                </div>
                @if($item->description)
                  <p id="liveDesc" class="text-[13px] text-neutral-600 dark:text-neutral-300 mt-2 line-clamp-2">{{ $item->description }}</p>
                @else
                  <p id="liveDesc" class="text-[13px] text-neutral-600 dark:text-neutral-300 mt-2 line-clamp-2"></p>
                @endif
              </div>
            </div>
          </article>
          <p class="mt-2 text-[11px] text-neutral-500">تتحدّث المعاينة مباشرةً عند تعديل الحقول.</p>
        </div>

        {{-- Quick info --}}
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-5 shadow-sm">
          <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100 mb-2">معلومات</h3>
          <ul class="text-xs text-neutral-600 dark:text-neutral-300 space-y-1">
            <li>تم الإنشاء: <span class="font-medium">{{ optional($item->created_at)->format('Y-m-d H:i') }}</span></li>
            <li>آخر تحديث: <span class="font-medium">{{ optional($item->updated_at)->diffForHumans() }}</span></li>
            <li>المعرّف: <span class="font-mono">{{ $item->id }}</span></li>
          </ul>
        </div>
      </aside>
    </div>

    {{-- Sticky action bar --}}
    <div class="sticky bottom-2">
      <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white/90 dark:bg-neutral-900/90 backdrop-blur p-3 flex items-center justify-between gap-2 shadow-sm">
        <div class="text-[11px] text-neutral-500" id="unsavedHint">لا توجد تغييرات غير محفوظة.</div>
        <div class="flex items-center gap-2">
          <a href="{{ $backUrl }}"
             class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 px-4 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
            إلغاء
          </a>
          <button id="saveBtn"
                  class="inline-flex items-center gap-2 rounded-xl bg-neutral-900 text-white px-5 py-2 text-sm font-medium hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M5 12h14v2H5z"/></svg>
            تحديث ⌘/Ctrl+S
          </button>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
  (function () {
    const $ = (sel) => document.querySelector(sel);

    const form = $('#editForm');
    const name = $('#name');
    const price = $('#price');
    const category = $('#category_id');
    const desc = $('#description');
    const tags = $('#tags');
    const sku = $('#sku');
    const unsaved = $('#unsavedHint');
    const saveBtn = $('#saveBtn');

    const currentImg = $('#currentImage');
    const imageInput = $('#imageInput');
    const previewWrap = $('#previewWrap');
    const previewImg = $('#previewImage');

    // Live preview nodes
    const liveName = $('#liveName');
    const livePrice = $('#livePrice');
    const liveCategory = $('#liveCategory');
    const liveDesc = $('#liveDesc');
    const liveCardImage = $('#liveCardImage');
    const liveAvail = $('#liveAvail');
    const availabilityLabel = $('#availabilityLabel');

    // Counters
    const nameCount = $('#nameCount');
    const descCount = $('#descCount');

    // Track dirty state
    let dirty = false;
    const markDirty = () => {
      if (!dirty) {
        dirty = true;
        unsaved.textContent = 'هناك تغييرات غير محفوظة.';
      }
    };

    // Update counters
    const setCount = (el, counterEl, max) => {
      const len = (el.value || '').length;
      counterEl.textContent = `${len}/${max}`;
    };
    if (name && nameCount) setCount(name, nameCount, 120);
    if (desc && descCount) setCount(desc, descCount, 400);
    name?.addEventListener('input', () => { setCount(name, nameCount, 120); markDirty(); liveName.textContent = name.value || '—'; });
    desc?.addEventListener('input', () => { setCount(desc, descCount, 400); markDirty(); liveDesc.textContent = desc.value || ''; });
    price?.addEventListener('input', () => { markDirty(); livePrice.textContent = price.value ? `DH ${Number(price.value).toFixed(2)}` : 'DH 0.00'; });
    category?.addEventListener('change', () => { markDirty(); liveCategory.textContent = 'الفئة: ' + (category.options[category.selectedIndex]?.text || '—'); });
    form?.addEventListener('change', (e) => {
      // toggle label for availability instantly
      if (e.target.name === 'is_available') {
        const checked = e.target.checked;
        availabilityLabel.textContent = checked ? 'متاح' : 'غير متاح';
        liveAvail.textContent = checked ? 'متاح' : 'غير متاح';
        liveAvail.className =
          'absolute -end-2 -top-2 inline-flex items-center rounded-full text-[10px] px-2 py-0.5 border ' +
          (checked ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200');
      }
    });

    // Image preview + basic validation (<=2MB)
    const setPreview = (file) => {
      if (!file) return;
      if (file.size > 2 * 1024 * 1024) {
        alert('حجم الصورة يتجاوز 2MB. الرجاء اختيار صورة أصغر.');
        imageInput.value = '';
        return;
      }
      const url = URL.createObjectURL(file);
      previewImg.src = url;
      previewWrap.classList.remove('hidden');
      currentImg.src = url;
      liveCardImage.src = url;
      markDirty();
    };
    imageInput?.addEventListener('change', (e) => setPreview(e.target.files?.[0]));

    // Drag & Drop area
    const dropZone = imageInput?.closest('label');
    if (dropZone) {
      ['dragenter','dragover'].forEach(evt => dropZone.addEventListener(evt, (e) => {
        e.preventDefault(); e.stopPropagation();
        dropZone.classList.add('ring-2','ring-blue-500');
      }));
      ['dragleave','drop'].forEach(evt => dropZone.addEventListener(evt, (e) => {
        e.preventDefault(); e.stopPropagation();
        dropZone.classList.remove('ring-2','ring-blue-500');
      }));
      dropZone.addEventListener('drop', (e) => {
        const file = e.dataTransfer?.files?.[0];
        if (file) {
          imageInput.files = e.dataTransfer.files;
          setPreview(file);
        }
      });
    }

    // Unsaved changes guard
    window.addEventListener('beforeunload', (e) => {
      if (!dirty) return;
      e.preventDefault();
      e.returnValue = '';
    });
    form?.addEventListener('submit', () => { dirty = false; unsaved.textContent = 'جارٍ الحفظ...'; });

    // Keyboard shortcut: Ctrl/Cmd+S
    window.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
        e.preventDefault();
        saveBtn?.click();
      }
    });
  })();
</script>
@endpush
