{{-- resources/views/orders/create.blade.php --}}
@extends('layouts.app')
@section('title','طلب جديد')

@section('content')
<form id="orderForm" action="{{ route('orders.store') }}" method="POST" class="space-y-5">
  @csrf

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight text-neutral-900 dark:text-neutral-100">طلب جديد</h1>
      <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">اختر الأصناف وعدّل الكميات ثم احفظ الطلب.</p>
    </div>
    <div class="text-sm text-neutral-600 dark:text-neutral-400">النادل: <span class="font-medium">{{ auth()->user()->name }}</span></div>
  </div>

  {{-- Table & notes --}}
  <div class="grid gap-3 md:grid-cols-2">
    <label class="block">
      <div class="text-sm mb-1">رقم الطاولة</div>
      <input type="number" name="table_number" min="1"
             class="w-full rounded-xl border border-neutral-300/60 dark:border-neutral-700/70 bg-white dark:bg-neutral-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
             placeholder="مثال: 5" value="{{ old('table_number') }}">
    </label>

    <label class="block">
      <div class="text-sm mb-1">ملاحظات</div>
      <input type="text" name="notes"
             class="w-full rounded-xl border border-neutral-300/60 dark:border-neutral-700/70 bg-white dark:bg-neutral-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
             placeholder="أي ملاحظات…" value="{{ old('notes') }}">
    </label>
  </div>

  {{-- Search + Tabs --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/70 dark:bg-neutral-900/60 backdrop-blur p-4">
    <div class="flex flex-col gap-3">
      {{-- Search --}}
      <div class="flex items-center gap-2">
        <div class="relative flex-1">
          <input id="searchInput" type="text" placeholder="ابحث باسم الصنف…"
                 class="w-full rounded-xl border border-neutral-300/60 dark:border-neutral-700/70 bg-white dark:bg-neutral-900 pl-10 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
          <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M21 21l-5.2-5.2M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
          </svg>
        </div>
        <button type="button" id="clearSearch"
                class="rounded-xl border px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">
          مسح
        </button>
      </div>

      {{-- Category Tabs --}}
      <div id="catTabs"
           class="flex gap-2 overflow-x-auto pb-2 border-b border-neutral-200/60 dark:border-neutral-800/80">
        @foreach($cats as $i => $cat)
          <button type="button"
                  data-tab="#tab-{{ $cat->id }}"
                  class="tab-btn shrink-0 px-4 py-2 rounded-xl text-sm transition
                         {{ $i === 0 ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900' : 'hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
            {{ $cat->name }}
            <span class="text-[11px] opacity-60">({{ $cat->items->count() }})</span>
          </button>
        @endforeach
      </div>
    </div>

    {{-- Panels --}}
    <div class="mt-4 space-y-6">
      @foreach($cats as $i => $cat)
        <div id="tab-{{ $cat->id }}" class="{{ $i === 0 ? '' : 'hidden' }}">
          @if($cat->items->isNotEmpty())
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
              @foreach($cat->items as $item)
                @php
                  $available = isset($item->is_available) ? (bool)$item->is_available : true;
                @endphp
                {{-- Product card --}}
                <div
                  class="product-card group rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80
                         bg-white/80 dark:bg-neutral-900/70 p-3 hover:shadow-sm transition
                         flex items-center gap-4"
                  data-name="{{ \Illuminate\Support\Str::lower($item->name) }}"
                  data-available="{{ $available ? '1' : '0' }}">

                  {{-- Thumbnail --}}
                  <div class="relative shrink-0">
                    <img
                      src="{{ $item->image_url }}"
                      alt="{{ $item->name }}"
                      loading="lazy"
                      class="aspect-square w-16 sm:w-20 rounded-xl object-cover ring-1 ring-black/10 dark:ring-white/10 {{ $available ? '' : 'opacity-50' }}" />
                    @if(!$available)
                      <span class="absolute bottom-1 right-1 text-[10px] px-1.5 py-0.5 rounded bg-rose-600 text-white">غير متاح</span>
                    @endif
                  </div>

                  {{-- Name + meta --}}
                  <div class="flex-1 min-w-0 text-right">
                    <div class="font-semibold break-words leading-snug" title="{{ $item->name }}">
                      {{ $item->name }}
                    </div>
                    <div class="text-xs opacity-70 leading-snug">
                      {{ $item->category?->name }} — <span class="tabular-nums">DH {{ number_format((float)$item->price,2) }}</span>
                    </div>
                  </div>

                  {{-- Quantity control --}}
                  <div class="flex items-center gap-2 shrink-0">
                    <button type="button"
                            class="qty-dec size-8 rounded-lg border border-neutral-300/60 dark:border-neutral-700/70 grid place-items-center hover:bg-neutral-100 dark:hover:bg-neutral-800 disabled:opacity-40"
                            data-id="{{ $item->id }}" {{ $available ? '' : 'disabled' }}>−</button>

                    <input type="number"
                           class="qty-input w-16 text-center rounded-lg border border-neutral-300/60 dark:border-neutral-700/70 bg-white dark:bg-neutral-900"
                           value="0" min="0" max="99" step="1"
                           data-id="{{ $item->id }}"
                           data-price="{{ (float)$item->price }}"
                           data-name="{{ $item->name }}"
                           {{ $available ? '' : 'disabled' }}>

                    <button type="button"
                            class="qty-inc size-8 rounded-lg border border-neutral-300/60 dark:border-neutral-700/70 grid place-items-center hover:bg-neutral-100 dark:hover:bg-neutral-800 disabled:opacity-40"
                            data-id="{{ $item->id }}" {{ $available ? '' : 'disabled' }}>＋</button>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="col-span-full text-center opacity-70 py-10">لا توجد عناصر متاحة في هذه الفئة.</div>
          @endif
        </div>
      @endforeach
    </div>
  </div>

  {{-- Cart summary / submit (sticky footer-style card) --}}
  <div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/80 dark:bg-neutral-900/70 backdrop-blur p-4">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
      <div class="flex flex-wrap items-center gap-3 text-sm">
        <span>الأصناف المختارة: <span id="selectedCount" class="font-semibold tabular-nums">0</span></span>
        <span class="hidden lg:inline opacity-40">|</span>
        <span>الإجمالي: <span id="grandTotal" class="font-bold tabular-nums">DH 0.00</span></span>
        <span class="opacity-60 text-xs">(يُحفظ فقط ما كميته ≥ 1)</span>
      </div>

      <div class="flex items-center gap-2">
        <button type="button" id="toggleCart"
                class="rounded-xl border px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">
          عرض/إخفاء السلة
        </button>
        <button type="button" id="clearAll"
                class="rounded-xl border px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">
          مسح الاختيارات
        </button>
        <button type="submit" id="submitBtn"
                class="rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 disabled:opacity-40 disabled:cursor-not-allowed">
          حفظ الطلب
        </button>
      </div>
    </div>

    {{-- Collapsible mini-cart list --}}
    <div id="cartList" class="mt-3 hidden">
      <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 overflow-hidden">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-50 dark:bg-neutral-800">
            <tr>
              <th class="p-2 text-right">الصنف</th>
              <th class="p-2">السعر</th>
              <th class="p-2">الكمية</th>
              <th class="p-2">المجموع</th>
              <th class="p-2">حذف</th>
            </tr>
          </thead>
          <tbody id="cartBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Hidden inputs container (for form submit) --}}
  <div id="itemsContainer"></div>
</form>
@endsection

@push('scripts')
<script>
  (function(){
    // ========= Helpers =========
    function $(s, r){ return (r||document).querySelector(s); }
    function $all(s, r){ return Array.prototype.slice.call((r||document).querySelectorAll(s)); }
    function toNumber(v, def){ var n = parseFloat(v); return isNaN(n) ? (def||0) : n; }
    function fmt(n){ return 'DH ' + Number(n).toFixed(2); }

    var itemsContainer = $('#itemsContainer');
    var totalSpan      = $('#grandTotal');
    var countSpan      = $('#selectedCount');
    var submitBtn      = $('#submitBtn');
    var cartList       = $('#cartList');
    var cartBody       = $('#cartBody');

    // ========= Tabs =========
    $all('#catTabs .tab-btn').forEach(function(btn){
      btn.addEventListener('click', function(){
        var target = btn.getAttribute('data-tab');
        $all('#catTabs .tab-btn').forEach(function(b){
          b.classList.remove('bg-neutral-900','text-white','dark:bg-white','dark:text-neutral-900');
        });
        btn.classList.add('bg-neutral-900','text-white','dark:bg-white','dark:text-neutral-900');
        $all('[id^="tab-"]').forEach(function(p){ p.classList.add('hidden'); });
        var panel = $(target); if (panel) panel.classList.remove('hidden');
        // Reset search visual filter per active tab
        applySearch($('#searchInput') ? $('#searchInput').value : '');
      });
    });

    // ========= Quantity state & syncing =========
    function refreshHiddenInputsAndSummary(){
      itemsContainer.innerHTML = '';
      cartBody.innerHTML = '';

      var grand = 0;
      var pickedCount = 0;

      $all('.qty-input').forEach(function(inp){
        var qty = parseInt(inp.value || 0, 10);
        if (qty > 0) {
          var id    = inp.getAttribute('data-id');
          var price = toNumber(inp.getAttribute('data-price'), 0);
          var name  = inp.getAttribute('data-name') || ('#'+id);
          var line  = price * qty;

          // Hidden inputs
          var wrap = document.createElement('div');
          wrap.innerHTML =
            '<input type="hidden" name="items['+id+'][menu_item_id]" value="'+id+'">' +
            '<input type="hidden" name="items['+id+'][quantity]" value="'+qty+'">';
          itemsContainer.appendChild(wrap);

          // Cart table
          var tr = document.createElement('tr');
          tr.className = 'border-t';
          tr.innerHTML =
            '<td class="p-2">'+ name +'</td>' +
            '<td class="p-2 tabular-nums">'+ fmt(price) +'</td>' +
            '<td class="p-2">' +
              '<div class="inline-flex items-center gap-2">' +
                '<button type="button" class="mini-dec rounded-lg border px-2 py-1" data-id="'+id+'">−</button>' +
                '<span class="tabular-nums">'+ qty +'</span>' +
                '<button type="button" class="mini-inc rounded-lg border px-2 py-1" data-id="'+id+'">＋</button>' +
              '</div>' +
            '</td>' +
            '<td class="p-2 tabular-nums">'+ fmt(line) +'</td>' +
            '<td class="p-2"><button type="button" class="mini-del text-rose-600" data-id="'+id+'">حذف</button></td>';
          cartBody.appendChild(tr);

          grand += line;
          pickedCount++;
        }
      });

      totalSpan.textContent = fmt(grand);
      countSpan.textContent = pickedCount;
      submitBtn.disabled = pickedCount === 0;

      // Mini-cart actions
      $all('.mini-inc').forEach(function(b){
        b.addEventListener('click', function(){
          var id = b.getAttribute('data-id');
          var inp = $('.qty-input[data-id="'+id+'"]');
          if (!inp) return;
          var v = Math.min(99, (parseInt(inp.value || 0, 10) + 1));
          inp.value = v;
          refreshHiddenInputsAndSummary();
        });
      });
      $all('.mini-dec').forEach(function(b){
        b.addEventListener('click', function(){
          var id = b.getAttribute('data-id');
          var inp = $('.qty-input[data-id="'+id+'"]');
          if (!inp) return;
          var v = Math.max(0, (parseInt(inp.value || 0, 10) - 1));
          inp.value = v;
          refreshHiddenInputsAndSummary();
        });
      });
      $all('.mini-del').forEach(function(b){
        b.addEventListener('click', function(){
          var id = b.getAttribute('data-id');
          var inp = $('.qty-input[data-id="'+id+'"]');
          if (!inp) return;
          inp.value = 0;
          refreshHiddenInputsAndSummary();
        });
      });
    }

    function adjustQty(id, delta){
      var inp = $('.qty-input[data-id="'+id+'"]');
      if (!inp || inp.disabled) return;
      var v = parseInt(inp.value || 0, 10);
      if (isNaN(v)) v = 0;
      v = Math.max(0, Math.min(99, v + delta));
      inp.value = v;
      refreshHiddenInputsAndSummary();
    }

    $all('.qty-inc').forEach(function(btn){
      btn.addEventListener('click', function(){ adjustQty(btn.getAttribute('data-id'), +1); });
    });
    $all('.qty-dec').forEach(function(btn){
      btn.addEventListener('click', function(){ adjustQty(btn.getAttribute('data-id'), -1); });
    });
    $all('.qty-input').forEach(function(inp){
      inp.addEventListener('input', function(){
        var v = parseInt(inp.value || 0, 10);
        if (isNaN(v) || v < 0) v = 0;
        if (v > 99) v = 99;
        inp.value = v;
        refreshHiddenInputsAndSummary();
      });
    });

    // ========= Search (filters only visible panel) =========
    function normalize(s){ return (s||'').toString().toLowerCase().trim(); }
    function applySearch(q){
      var current = (function(){
        var t = null;
        $all('#catTabs .tab-btn').forEach(function(btn){
          if (btn.classList.contains('bg-neutral-900') || btn.classList.contains('dark:bg-white')) {
            t = btn.getAttribute('data-tab');
          }
        });
        return t;
      })();
      var panel = current ? $(current) : null;
      if (!panel) return;

      var qn = normalize(q);
      $all('.product-card', panel).forEach(function(card){
        var name = normalize(card.getAttribute('data-name'));
        if (!qn || name.indexOf(qn) !== -1) {
          card.classList.remove('hidden');
        } else {
          card.classList.add('hidden');
        }
      });
    }
    var searchInput = $('#searchInput');
    var clearSearch = $('#clearSearch');
    if (searchInput) {
      var tId = null;
      searchInput.addEventListener('input', function(){
        if (tId) clearTimeout(tId);
        tId = setTimeout(function(){ applySearch(searchInput.value); }, 120);
      });
    }
    if (clearSearch) {
      clearSearch.addEventListener('click', function(){
        if (!searchInput) return;
        searchInput.value = '';
        applySearch('');
      });
    }

    // ========= Mini-cart toggle / Clear all =========
    $('#toggleCart')?.addEventListener('click', function(){
      if (!cartList) return;
      var hidden = cartList.classList.contains('hidden');
      if (hidden) cartList.classList.remove('hidden'); else cartList.classList.add('hidden');
    });
    $('#clearAll')?.addEventListener('click', function(){
      $all('.qty-input').forEach(function(inp){ inp.value = 0; });
      refreshHiddenInputsAndSummary();
    });

    // Init
    refreshHiddenInputsAndSummary();
    applySearch('');
  })();
</script>
@endpush
