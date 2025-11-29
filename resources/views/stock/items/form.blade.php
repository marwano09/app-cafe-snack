@csrf
<div class="grid md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm mb-1">الاسم</label>
    <input name="name" value="{{ old('name', $item->name ?? '') }}" required class="w-full input">
    @error('name')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm mb-1">SKU (اختياري)</label>
    <input name="sku" value="{{ old('sku', $item->sku ?? '') }}" class="w-full input">
    @error('sku')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm mb-1">الوحدة</label>
    <input name="unit" value="{{ old('unit', $item->unit ?? 'u') }}" class="w-full input" placeholder="u, L, ml, g…">
    @error('unit')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm mb-1">الحد الأدنى للتنبيه</label>
    <input type="number" step="0.01" name="min_qty" value="{{ old('min_qty', $item->min_qty ?? 0) }}" class="w-full input">
    @error('min_qty')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm mb-1">الكمية الحالية</label>
    <input type="number" step="0.01" name="current_qty" value="{{ old('current_qty', $item->current_qty ?? 0) }}" class="w-full input">
    @error('current_qty')<div class="text-rose-500 text-xs mt-1">{{ $message }}</div>@enderror
    <p class="text-xs opacity-60 mt-1">ملاحظة: التغييرات المستقبلية تتم عبر الشراء/التسوية — هذا الحقل لتصحيح البداية فقط.</p>
  </div>
</div>
