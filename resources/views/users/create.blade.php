@extends('layouts.app')
@section('title','مستخدم جديد')

@section('content')
<h1 class="text-xl font-bold mb-4">إنشاء مستخدم</h1>

@if($errors->any())
  <div class="mb-3 rounded-xl border border-red-200 bg-red-50 text-red-900 p-3 text-sm">
    <ul class="list-disc pr-5 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<form method="POST" action="{{ route('users.store') }}" class="grid gap-4 max-w-lg">
  @csrf
  <div>
    <label class="block text-sm mb-1">الاسم</label>
    <input name="name" value="{{ old('name') }}" class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900" required>
  </div>

  <div>
    <label class="block text-sm mb-1">اسم المستخدم (اختياري)</label>
    <input name="username" value="{{ old('username') }}" class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900">
  </div>

  <div>
    <label class="block text-sm mb-1">البريد الإلكتروني (اختياري)</label>
    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900">
  </div>

  <div>
    <label class="block text-sm mb-1">كلمة المرور</label>
    <input type="password" name="password" class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900" required>
  </div>

  <div>
    <label class="block text-sm mb-1">الدور</label>
    <select name="role" class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900" required>
      @foreach($roles as $r)
        <option value="{{ $r }}" @selected(old('role')===$r)>{{ $r }}</option>
      @endforeach
    </select>
  </div>

  <div class="flex gap-2">
    <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-xl border">رجوع</a>
    <button class="px-4 py-2 rounded-xl bg-neutral-900 text-white">حفظ</button>
  </div>
</form>
@endsection
