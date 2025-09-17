@extends('layouts.app')
@section('title','الطلبات')

@section('content')
<div class="flex items-center justify-between mb-4">
  <a href="{{ route('orders.create') }}" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">طلب جديد</a>

  <div class="text-sm">
    ترتيب حسب التاريخ:
    <a class="px-2 py-1 rounded {{ ($sort??'desc')==='desc'?'bg-neutral-200 dark:bg-neutral-800':'' }}"
       href="{{ route('orders.index', ['sort'=>'desc']) }}">الأحدث</a>
    <a class="px-2 py-1 rounded {{ ($sort??'desc')==='asc'?'bg-neutral-200 dark:bg-neutral-800':'' }}"
       href="{{ route('orders.index', ['sort'=>'asc']) }}">الأقدم</a>
  </div>
</div>

<div class="rounded-2xl border border-neutral-200/60 dark:border-neutral-800/80 bg-white/60 dark:bg-neutral-900/60 p-4 overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-neutral-50 dark:bg-neutral-800">
      <tr>
        <th class="p-2 text-right">#</th>
        <th class="p-2">النادل</th>
        <th class="p-2">الحالة</th>
        <th class="p-2">المجموع</th>
        <th class="p-2">التاريخ</th>
        <th class="p-2">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      @foreach($orders as $o)
        <tr class="border-t">
          <td class="p-2">{{ $o->id }}</td>
          <td class="p-2">{{ optional($o->waiter)->name ?? '—' }}</td>
          <td class="p-2">{{ $o->status }}</td>
          <td class="p-2">DH {{ number_format((float)$o->total,2) }}</td>
          <td class="p-2">{{ $o->created_at->format('H:i Y-m-d') }}</td>
          <td class="p-2 flex gap-2">
            <a class="text-sky-500 hover:underline" href="{{ route('orders.show',$o) }}">عرض</a>
            @can('update', $o) {{-- policy-less simple gate handled in controller --}}
            <a class="text-amber-500 hover:underline" href="{{ route('orders.edit',$o) }}">تعديل</a>
            @endcan
            <form method="POST" action="{{ route('orders.destroy',$o) }}"
                  onsubmit="return confirm('حذف هذا الطلب نهائياً؟')">
              @csrf @method('DELETE')
              <button class="text-rose-600 hover:underline">حذف</button>
            </form>
          </td>
        </tr>
      @endforeach
      @if($orders->isEmpty())
        <tr><td colspan="6" class="p-4 text-center opacity-70">لا توجد طلبات.</td></tr>
      @endif
    </tbody>
  </table>

  <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
