@extends('layouts.app')
@section('title','تعديل مستخدم')

@section('content')
{{-- Header / Back --}}
<div class="mb-5 flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-semibold tracking-tight text-neutral-900 dark:text-neutral-100">
      تعديل المستخدم: {{ $user->name }}
    </h1>
    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">حدّث بيانات الحساب ثم اضغط "تحديث".</p>
  </div>
  <a href="{{ route('users.index') }}"
     class="inline-flex items-center gap-2 rounded-xl border px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 18l-6-6 6-6"/></svg>
    رجوع
  </a>
</div>

{{-- Alerts --}}
@if($errors->any())
  <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-900 p-3 text-sm">
    <ul class="list-disc pr-5 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

{{-- Form Card --}}
<div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-5 shadow-sm">
  <form method="POST" action="{{ route('users.update', $user) }}" class="grid gap-5 max-w-3xl" id="userEditForm" novalidate>
    @csrf @method('PUT')

    {{-- Name + Username --}}
    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm mb-1 font-medium">الاسم <span class="text-rose-600">*</span></label>
        <input name="name" value="{{ old('name', $user->name) }}" required autocomplete="name"
               class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
        @error('name')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm mb-1 font-medium">اسم المستخدم</label>
        <input name="username" value="{{ old('username', $user->username) }}" autocomplete="username"
               class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
        @error('username')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
      </div>
    </div>

    {{-- Email + Role --}}
    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm mb-1 font-medium">البريد الإلكتروني</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email" dir="ltr"
               class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
        @error('email')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm mb-1 font-medium">الدور <span class="text-rose-600">*</span></label>
        <select name="role" required
                class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
          @foreach($roles as $r)
            <option value="{{ $r }}" @selected(($user->roles->pluck('name')->first())===$r)>{{ $r }}</option>
          @endforeach
        </select>
        @error('role')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
      </div>
    </div>

    {{-- Password (optional) --}}
    <div>
      <label class="block text-sm mb-1 font-medium">كلمة المرور (اتركها فارغة إن لم تُرِد التغيير)</label>
      <div class="flex gap-2">
        <div class="relative flex-1">
          <input type="password" name="password" id="passwordInput" autocomplete="new-password"
                 class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-4 py-2 text-sm pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400" placeholder="••••••••">
          <button type="button" id="togglePass"
                  class="absolute inset-y-0 left-2 my-auto size-7 grid place-items-center rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800"
                  title="إظهار/إخفاء">
            <svg id="eyeIcon" class="w-4 h-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Zm10 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
            </svg>
          </button>
        </div>
        <button type="button" id="genPass"
                class="rounded-xl border px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800"
                title="توليد كلمة مرور قوية">توليد</button>
      </div>

      {{-- strength meter (only visual) --}}
      <div class="mt-2">
        <div class="h-1.5 w-full rounded-full bg-neutral-200 dark:bg-neutral-800 overflow-hidden">
          <div id="strengthBar" class="h-full w-0 rounded-full bg-emerald-500 transition-all duration-300"></div>
        </div>
        <div id="strengthText" class="text-[11px] mt-1 text-neutral-500 dark:text-neutral-400">قوة كلمة المرور: —</div>
      </div>

      @error('password')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap items-center gap-2 pt-2">
      <a href="{{ route('users.index') }}"
         class="inline-flex items-center gap-2 rounded-xl border px-4 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">
        إلغاء
      </a>
      <button id="submitBtn"
              class="inline-flex items-center gap-2 rounded-xl bg-neutral-900 text-white px-5 py-2 text-sm hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
        <svg id="btnSpinner" class="w-4 h-4 hidden animate-spin" viewBox="0 0 24 24" fill="none">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4A4 4 0 0 0 8 12H4Z"></path>
        </svg>
        تحديث
      </button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
  // Show/Hide password (front-end only)
  (function () {
    var input = document.getElementById('passwordInput');
    var btn   = document.getElementById('togglePass');
    var icon  = document.getElementById('eyeIcon');
    if (!input || !btn) return;
    btn.addEventListener('click', function () {
      var isPwd = input.getAttribute('type') === 'password';
      input.setAttribute('type', isPwd ? 'text' : 'password');
      icon.style.opacity = isPwd ? '1' : '0.7';
    });
  })();

  // Generate strong password & copy (optional)
  (function () {
    var btn = document.getElementById('genPass');
    var input = document.getElementById('passwordInput');
    if (!btn || !input) return;

    function gen(len) {
      var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%^&*()-_=+[]{}';
      var out = '';
      for (var i=0; i<len; i++) out += chars[Math.floor(Math.random()*chars.length)];
      return out;
    }

    btn.addEventListener('click', function () {
      var pwd = gen(12);
      input.value = pwd;
      input.dispatchEvent(new Event('input'));
      if (navigator.clipboard) {
        navigator.clipboard.writeText(pwd).then(function(){
          btn.textContent = 'تم التوليد ✓';
          setTimeout(function(){ btn.textContent = 'توليد'; }, 1200);
        });
      }
    });
  })();

  // Simple strength meter (visual)
  (function () {
    var input = document.getElementById('passwordInput');
    var bar   = document.getElementById('strengthBar');
    var txt   = document.getElementById('strengthText');
    if (!input || !bar) return;

    function score(s) {
      var sc = 0;
      if (!s) return 0;
      if (s.length >= 8) sc += 1;
      if (s.length >= 12) sc += 1;
      if (/[A-Z]/.test(s)) sc += 1;
      if (/[a-z]/.test(s)) sc += 1;
      if (/\d/.test(s)) sc += 1;
      if (/[^A-Za-z0-9]/.test(s)) sc += 1;
      return Math.min(sc, 6);
    }
    function render(sc) {
      var pct = (sc / 6) * 100;
      bar.style.width = pct + '%';
      var label = 'ضعيفة', color = '#ef4444';
      if (sc >= 4) { label = 'جيدة'; color = '#f59e0b'; }
      if (sc >= 5) { label = 'قوية'; color = '#10b981'; }
      bar.style.backgroundColor = color;
      if (txt) txt.textContent = 'قوة كلمة المرور: ' + label;
    }

    input.addEventListener('input', function () { render(score(input.value)); });
    render(score(input.value));
  })();

  // Prevent double submit (UI only)
  (function () {
    var form = document.getElementById('userEditForm');
    var btn  = document.getElementById('submitBtn');
    var spn  = document.getElementById('btnSpinner');
    if (!form || !btn) return;
    form.addEventListener('submit', function () {
      btn.setAttribute('disabled', 'disabled');
      if (spn) spn.classList.remove('hidden');
    });
  })();
</script>
@endpush
