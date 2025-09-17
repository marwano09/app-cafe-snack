@extends('layouts.app')
@section('title','تعديل مستخدم')

@section('content')
<h1 class="text-xl font-bold mb-4">تعديل المستخدم: {{ $user->name }}</h1>

@if($errors->any())
  <div class="mb-3 rounded-xl border border-red-200 bg-red-50 text-red-900 p-3 text-sm">
    <ul class="list-disc pr-5 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<form method="POST" action="{{ route('users.update', $user) }}" class="grid gap-4 max-w-lg">
  @csrf @method('PUT')

  <div>
    <label class="block text-sm mb-1">الاسم</label>
    <input name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900" required>
  </div>

  <div>
    <label class="block text-sm mb-1">اسم المستخدم</label>
    <input name="username" value="{{ old('username', $user->username) }}" class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900">
  </div>

  <div>
    <label class="block text-sm mb-1">البريد الإلكتروني</label>
    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900">
  </div>

  <div>
    <label class="block text-sm mb-1">كلمة المرور (اتركها فارغة إن لم تُرِد التغيير)</label>
    <input type="password" name="password" class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900">
  </div>

  <div>
    <label class="block text-sm mb-1">الدور</label>
    <select name="role" class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-neutral-900" required>
      @foreach($roles as $r)
        <option value="{{ $r }}" @selected($user->roles->pluck('name')->first()===$r)>{{ $r }}</option>
      @endforeach
    </select>
  </div>

  <div class="flex gap-2">
    <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-xl border">رجوع</a>
    <button class="px-4 py-2 rounded-xl bg-neutral-900 text-white">تحديث</button>
  </div>
</form>
@endsection
