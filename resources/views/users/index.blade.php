@extends('layouts.app')
@section('title','المستخدمون')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-bold">المستخدمون</h1>
  <a href="{{ route('users.create') }}" class="rounded-xl bg-neutral-900 text-white px-4 py-2 hover:opacity-90">
    + مستخدم جديد
  </a>
</div>

@if(session('ok'))
  <div class="mb-3 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-900 p-3 text-sm">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="mb-3 rounded-xl border border-red-200 bg-red-50 text-red-900 p-3 text-sm">
    @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
  </div>
@endif

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
  @forelse($users as $u)
    <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4">
      <div class="font-semibold">{{ $u->name }}</div>
      <div class="text-xs opacity-70 mt-1">username: {{ $u->username ?? '—' }}</div>
      <div class="text-xs opacity-70">email: {{ $u->email ?? '—' }}</div>
      <div class="text-xs mt-2">
        الدور:
        @foreach($u->roles as $r)
          <span class="px-2 py-0.5 rounded bg-neutral-100 dark:bg-neutral-800 text-xs">{{ $r->name }}</span>
        @endforeach
      </div>
      <div class="mt-3 flex gap-2">
        <a href="{{ route('users.edit', $u) }}" class="px-3 py-1.5 rounded-lg border hover:bg-neutral-50 dark:hover:bg-neutral-800">تعديل</a>
        <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('حذف المستخدم؟')">
          @csrf @method('DELETE')
          <button class="px-3 py-1.5 rounded-lg border border-red-300 text-red-700 hover:bg-red-50">حذف</button>
        </form>
      </div>
    </div>
  @empty
    <div class="col-span-full text-center opacity-60">لا يوجد مستخدمون.</div>
  @endforelse
</div>

<div class="mt-6">{{ $users->links() }}</div>
@endsection
