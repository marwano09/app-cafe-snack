{{-- resources/views/orders/create.blade.php --}}
@extends('layouts.app')
@section('title','طلب جديد')

@section('content')
<form id="orderForm" action="{{ route('orders.store') }}" method="POST" class="space-y-4">
  @csrf

  {{-- Header --}}
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">طلب جديد</h1>
    <div class="text-sm opacity-70">{{ auth()->user()->name }}</div>
  </div>

  {{-- Table & notes --}}
  <div class="grid gap-3 md:grid-cols-2">
    <label class="block">
      <div class="text-sm mb-1">رقم الطاولة</div>
      <input type="number" name="table_number" min="1"
             class="w-full rounded-xl border border-neutral-300/60 dark:border-neutral-700/70 bg-white dark:bg-neutral-800 px-3 py-2"
             placeholder="مثال: 5" value="{{ old('table_number') }}">
    </label>

    <label class="block">
      <div class="text-sm mb-1">ملاحظات</div>
      <input type="text" name="notes"
             class="w-full rounded-xl border border-neutral-300/60 dark:border-neutral-700/70 bg-white dark:bg-neutral-800 px-3 py-2"
             placeholder="أي ملاحظات…" value="{{ old('notes') }}">
    </label>
  </div>

  {{-- Tabs: categories --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 p-4">
    <div id="catTabs"
         class="flex gap-2 overflow-x-auto pb-2 border-b border-neutral-200/60 dark:border-neutral-800/80">
      @foreach($cats as $i => $cat)
        <button type="button"
                data-tab="#tab-{{ $cat->id }}"
                class="tab-btn shrink-0 px-4 py-2 rounded-xl text-sm transition
                       {{ $i === 0 ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900' : 'hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
          {{ $cat->name }}
        </button>
      @endforeach
    </div>

    {{-- Panels --}}
    <div class="mt-4 space-y-6">
      @foreach($cats as $i => $cat)
        <div id="tab-{{ $cat->id }}" class="{{ $i === 0 ? '' : 'hidden' }} grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
          @forelse($cat->items as $item)
            {{-- Product card --}}
            <div
              class="group rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80
                     bg-white/80 dark:bg-neutral-900/70 p-3
                     hover:shadow-sm transition flex items-center gap-4">

              {{-- Thumbnail --}}
              <img
                src="{{ $item->image_url }}"
                alt="{{ $item->name }}"
                loading="lazy"
                class="aspect-square w-16 sm:w-20 rounded-xl object-cover ring-1 ring-black/10 shrink-0" />

              {{-- Name + meta --}}
              <div class="flex-1 min-w-0 text-right">
                <div class="font-semibold break-words leading-snug" title="{{ $item->name }}">
                  {{ $item->name }}
                </div>
                <div class="text-xs opacity-70 leading-snug">
                  {{ $item->category?->name }} — DH {{ number_format((float)$item->price,2) }}
                </div>
              </div>

              {{-- Quantity control --}}
              <div class="flex items-center gap-2 shrink-0">
                <button type="button"
                        class="qty-dec size-8 rounded-lg border border-neutral-300/60 dark:border-neutral-700/70 grid place-items-center hover:bg-neutral-100 dark:hover:bg-neutral-800"
                        data-id="{{ $item->id }}">−</button>

                <input type="number"
                       class="qty-input w-16 text-center rounded-lg border border-neutral-300/60 dark:border-neutral-700/70 bg-white dark:bg-neutral-800"
                       value="0" min="0" max="99" step="1"
                       data-id="{{ $item->id }}"
                       data-price="{{ (float)$item->price }}"
                       data-name="{{ $item->name }}">

                <button type="button"
                        class="qty-inc size-8 rounded-lg border border-neutral-300/60 dark:border-neutral-700/70 grid place-items-center hover:bg-neutral-100 dark:hover:bg-neutral-800"
                        data-id="{{ $item->id }}">＋</button>
              </div>
            </div>
          @empty
            <div class="col-span-full text-center opacity-70 py-10">لا توجد عناصر متاحة في هذه الفئة.</div>
          @endforelse
        </div>
      @endforeach
    </div>
  </div>

  {{-- Cart summary / submit --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 p-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div class="text-sm">
        <span>الإجمالي: </span>
        <span id="grandTotal" class="font-bold">DH 0.00</span>
        <span class="opacity-60 text-xs mx-2">(سيتم حفظ العناصر التي عددها ≥ 1 فقط)</span>
      </div>

      <button type="submit"
              class="rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2">
        حفظ الطلب
      </button>
    </div>
  </div>

  {{-- Hidden container where we’ll generate items[] inputs --}}
  <div id="itemsContainer"></div>
</form>
@endsection

@push('scripts')
<script>
  (function(){
    const $ = (s, r=document) => r.querySelector(s);
    const $$ = (s, r=document) => Array.from(r.querySelectorAll(s));

    // Tabs
    $$('#catTabs .tab-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const target = btn.getAttribute('data-tab');
        $$('#catTabs .tab-btn').forEach(b => b.classList.remove('bg-neutral-900', 'text-white', 'dark:bg-white', 'dark:text-neutral-900'));
        btn.classList.add('bg-neutral-900','text-white','dark:bg-white','dark:text-neutral-900');
        document.querySelectorAll('[id^="tab-"]').forEach(p => p.classList.add('hidden'));
        $(target)?.classList.remove('hidden');
      });
    });

    // Quantity controls
    const itemsContainer = $('#itemsContainer');
    const totalSpan = $('#grandTotal');

    function refreshHiddenInputs() {
      itemsContainer.innerHTML = '';
      let grand = 0;

      $$('.qty-input').forEach(inp => {
        const qty = parseInt(inp.value || 0, 10);
        if (qty > 0) {
          const id = inp.dataset.id;
          const price = parseFloat(inp.dataset.price || 0);
          grand += price * qty;

          const wrap = document.createElement('div');
          wrap.innerHTML = `
            <input type="hidden" name="items[${id}][menu_item_id]" value="${id}">
            <input type="hidden" name="items[${id}][quantity]" value="${qty}">
          `;
          itemsContainer.appendChild(wrap);
        }
      });

      totalSpan.textContent = 'DH ' + grand.toFixed(2);
    }

    $$('.qty-inc').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const inp = $('.qty-input[data-id="'+id+'"]');
        const v = Math.min(99, (parseInt(inp.value || 0, 10) + 1));
        inp.value = v;
        refreshHiddenInputs();
      });
    });

    $$('.qty-dec').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const inp = $('.qty-input[data-id="'+id+'"]');
        const v = Math.max(0, (parseInt(inp.value || 0, 10) - 1));
        inp.value = v;
        refreshHiddenInputs();
      });
    });

    $$('.qty-input').forEach(inp => {
      inp.addEventListener('input', () => {
        let v = parseInt(inp.value || 0, 10);
        if (isNaN(v) || v < 0) v = 0;
        if (v > 99) v = 99;
        inp.value = v;
        refreshHiddenInputs();
      });
    });

    refreshHiddenInputs();
  })();
</script>
@endpush
