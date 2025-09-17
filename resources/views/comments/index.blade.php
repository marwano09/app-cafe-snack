@extends('layouts.app')
@section('title','التعليقات')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-bold">التعليقات</h1>
  <a href="{{ route('comments.create') }}" class="px-3 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">تعليق جديد</a>
</div>

{{-- Sort bar --}}
<div class="mb-4">
  <form method="GET" class="flex gap-2">
    <select name="sort" onchange="this.form.submit()" class="rounded border p-2">
      <option value="desc" @selected($sort==='desc')>الأحدث أولاً</option>
      <option value="asc" @selected($sort==='asc')>الأقدم أولاً</option>
    </select>
  </form>
</div>

<div class="rounded-2xl border bg-white/60 dark:bg-neutral-900/60">
  <table class="min-w-full text-sm">
    <thead class="bg-neutral-100 dark:bg-neutral-800">
      <tr>
        <th class="p-2">#</th>
        <th class="p-2 text-right">المستخدم</th>
        <th class="p-2 text-right">النص</th>
        <th class="p-2 text-right">التاريخ</th>
        <th class="p-2">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      @forelse($comments as $c)
        <tr class="border-t">
          <td class="p-2">{{ $c->id }}</td>
          <td class="p-2">{{ $c->user->name }}</td>
          <td class="p-2">{{ $c->body }}</td>
          <td class="p-2">{{ $c->created_at->format('Y-m-d H:i') }}</td>
          <td class="p-2">
            <a href="{{ route('comments.edit',$c) }}" class="text-sky-600">تعديل</a>
            <form action="{{ route('comments.destroy',$c) }}" method="POST" class="inline"
                  onsubmit="return confirm('حذف هذا التعليق؟')">
              @csrf @method('DELETE')
              <button class="text-red-600 ml-2">حذف</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="p-4 text-center opacity-70">لا توجد تعليقات.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $comments->links() }}</div>
@endsection
