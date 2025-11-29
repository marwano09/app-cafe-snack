@extends('layouts.app')
@section('title','سجل الحركات')

@section('content')
<div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4 bg-white dark:bg-neutral-900">
  <form method="GET" class="grid md:grid-cols-4 gap-3">
    <div>
      <label class="block text-sm mb-1">المادة</label>
      <select name="stock_item_id" class="w-full input">
        <option value="">الكل</option>
        @foreach($items as $si)
          <option value="{{ $si->id }}" @selected((string)request('stock_item_id')===(string)$si->id)>{{ $si->name }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm mb-1">النوع</label>
      <select name="type" class="w-full input">
        <option value="">الكل</option>
        <option value="PURCHASE" @selected(request('type')==='PURCHASE')>توريد</option>
        <option value="CONSUME"  @selected(request('type')==='CONSUME')>استهلاك</option>
        <option value="ADJUST"   @selected(request('type')==='ADJUST')>تسوية</option>
      </select>
    </div>
    <div>
      <label class="block text-sm mb-1">من تاريخ</label>
      <input type="date" name="from" value="{{ request('from') }}" class="w-full input">
    </div>
    <div>
      <label class="block text-sm mb-1">إلى تاريخ</label>
      <input type="date" name="to" value="{{ request('to') }}" class="w-full input">
    </div>
    <div class="md:col-span-4">
      <button class="rounded-xl border px-4 py-2">تصفية</button>
    </div>
  </form>
</div>

<div class="mt-4 overflow-x-auto rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
  <table class="min-w-full text-sm">
    <thead class="text-xs uppercase tracking-wide border-b border-neutral-200 dark:border-neutral-800">
      <tr class="text-right">
        <th class="px-3 py-2">التاريخ</th>
        <th class="px-3 py-2">المادة</th>
        <th class="px-3 py-2">النوع</th>
        <th class="px-3 py-2">الكمية</th>
        <th class="px-3 py-2">ملاحظة</th>
        <th class="px-3 py-2">بواسطة</th>
      </tr>
    </thead>
    <tbody>
      @forelse($movements as $mv)
        <tr class="border-b border-neutral-100 dark:border-neutral-800">
          <td class="px-3 py-2">{{ $mv->created_at->format('Y-m-d H:i') }}</td>
          <td class="px-3 py-2">{{ optional($mv->stockItem)->name }}</td>
          <td class="px-3 py-2">
            <span class="text-[10px] px-2 py-0.5 rounded-full border
              @if($mv->type==='PURCHASE') border-blue-400 text-blue-600
              @elseif($mv->type==='CONSUME') border-amber-400 text-amber-600
              @else border-neutral-400 text-neutral-600 @endif">
              {{ $mv->type }}
            </span>
          </td>
          <td class="px-3 py-2">{{ $mv->qty_change > 0 ? '+' : '' }}{{ $mv->qty_change }}</td>
          <td class="px-3 py-2">{{ $mv->note ?? '—' }}</td>
          <td class="px-3 py-2">{{ optional($mv->user)->name ?? 'system' }}</td>
        </tr>
      @empty
        <tr><td colspan="6" class="px-3 py-8 text-center opacity-60">لا توجد حركات.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $movements->links() }}</div>
@endsection
