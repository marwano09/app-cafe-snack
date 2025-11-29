@extends('layouts.app')
@section('title','حركة المخزون')

@section('content')
<h1 class="text-xl font-bold mb-4">حركة المخزون</h1>

<div class="rounded-2xl border overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-neutral-50 dark:bg-neutral-800">
      <tr>
        <th class="p-2 text-right">التاريخ</th>
        <th class="p-2 text-right">الصنف</th>
        <th class="p-2 text-right">النوع</th>
        <th class="p-2 text-right">الكمية</th>
        <th class="p-2 text-right">سبب</th>
      </tr>
    </thead>
    <tbody>
      @foreach($movements as $m)
      <tr class="border-t">
        <td class="p-2">{{ $m->created_at->format('Y-m-d H:i') }}</td>
        <td class="p-2">{{ $m->stock->name ?? '—' }}</td>
        <td class="p-2">
          <span class="px-2 py-0.5 rounded-full border
            {{ $m->type==='IN' ? 'border-emerald-600 text-emerald-700' : ($m->type==='OUT' ? 'border-red-600 text-red-700':'border-blue-600 text-blue-700') }}">
            {{ $m->type }}
          </span>
        </td>
        <td class="p-2">{{ rtrim(rtrim(number_format($m->qty,3,'.',''), '0'),'.') }}</td>
        <td class="p-2">{{ $m->reason }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $movements->links() }}</div>
@endsection
