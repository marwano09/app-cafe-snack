{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest')
@section('title','Golden Pool Academy – تسجيل الدخول')

@section('content')
<section dir="rtl" class="min-h-[92dvh] grid place-items-center bg-neutral-950 px-4 py-10">

  {{-- ===================== Styles (design only) ===================== --}}
  <style>
    :root{
      --gold-1:#f5d36c;  /* soft */
      --gold-2:#e7bf48;  /* brand */
      --gold-3:#b4891c;  /* deep */
      --ink:#0f1115;
    }

    /* Card intro */
    @keyframes fadeUp{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
    /* Soft sheen for buttons */
    @keyframes sheen{0%{transform:translateX(-120%)}100%{transform:translateX(120%)}}

    /* ===== Left gold panel with layered ribbons & watermark ===== */
    .gold-panel{
      position:relative;
      background:
        radial-gradient(140% 140% at 10% 0%, var(--gold-1) 0%, var(--gold-2) 42%, var(--gold-3) 100%);
      overflow:hidden;
      isolation:isolate;
    }
    .gold-panel::before,
    .gold-panel::after{
      content:""; position:absolute; inset:auto; pointer-events:none;
    }
    .gold-panel::before{
      right:-25%; top:-28%; width:150%; height:72%;
      background:linear-gradient(135deg,rgba(255,255,255,.25),rgba(255,255,255,0));
      transform:rotate(8deg);
      box-shadow:inset 0 0 0 1px rgba(255,255,255,.08);
      filter:saturate(1.1);
    }
    .gold-panel::after{
      left:-28%; bottom:-30%; width:150%; height:65%;
      background:linear-gradient(315deg,rgba(0,0,0,.20),rgba(0,0,0,0));
      transform:rotate(-6deg);
      box-shadow:inset 0 0 0 1px rgba(0,0,0,.08);
    }
    .gold-watermark{
      position:absolute; inset:0; opacity:.12; mix-blend:soft-light;
      background-position:center; background-size:cover;
      filter:grayscale(.2) contrast(1.05);
    }

    /* Overlapping notch tab (desktop) */
    @media(min-width:1024px){
      .gold-notch{
        position:absolute; right:-64px; top:96px; height:56px; width:190px;
        background:#fff; color:#0f172a; border-radius:30px;
        box-shadow:0 10px 25px -12px rgba(0,0,0,.3), inset 0 1px 0 rgba(0,0,0,.06);
        display:flex; align-items:center; justify-content:center; font-weight:800; letter-spacing:.6px;
      }
      .dark .gold-notch{ background:#0b0d11; color:#e5e7eb; border:1px solid rgba(255,255,255,.06) }
    }

    /* ===== Floating label fields (enterprise) ===== */
    .field{ position:relative; }
    .field-input{
      width:100%; min-height:52px; border-radius:14px;
      padding:14px 48px 12px 14px; /* RTL icon on the right, action on the left */
      background:#fff; border:1.5px solid #e5e7eb; color:#0f172a; outline:none;
      transition:border-color .2s, box-shadow .2s, background .2s;
      font-size:.98rem;
    }
    .dark .field-input{ background:#0f1115; color:#f8fafc; border-color:#1f2937; }
    .field:focus-within .field-input{
      border-color:var(--gold-2);
      box-shadow:0 0 0 4px rgba(231,191,72,.22);
    }
    .field-label{
      position:absolute; right:12px; top:14px; padding:0 .4rem;
      font-size:.92rem; color:#6b7280; background:transparent; border-radius:6px;
      pointer-events:none; transform-origin:right top;
      transition:transform .16s, top .16s, color .2s, background .2s;
    }
    .dark .field-label{ color:#9ca3af; }
    .field.filled .field-label,
    .field:focus-within .field-label{
      top:-10px; transform:scale(.82); color:#b45309; background:#fff;
    }
    .dark .field.filled .field-label,
    .dark .field:focus-within .field-label{ background:#0f1115; color:#f59e0b; }

    .field-icon{ position:absolute; right:12px; top:50%; transform:translateY(-50%); color:#6b7280 }
    .dark .field-icon{ color:#cbd5e1 }
    .field-action{
      position:absolute; left:10px; top:50%; transform:translateY(-50%);
      width:34px; height:34px; display:grid; place-items:center; border-radius:999px;
      color:#374151; cursor:pointer; transition:background .15s;
    }
    .field-action:hover{ background:rgba(0,0,0,.06) }
    .dark .field-action{ color:#e5e7eb }
    .dark .field-action:hover{ background:rgba(255,255,255,.06) }

    /* Autofill keep look */
    input.field-input:-webkit-autofill{ -webkit-text-fill-color:#0f172a; transition:background-color 9999s 0s }
    .dark input.field-input:-webkit-autofill{ -webkit-text-fill-color:#f8fafc }

  </style>

  {{-- ===================== Card ===================== --}}
  <div class="w-full max-w-[1080px] rounded-[26px] bg-white dark:bg-neutral-900 shadow-2xl overflow-hidden animate-[fadeUp_.45s_ease-out_both]">

    <div class="grid lg:grid-cols-2">

      {{-- ===== Left: black & gold panel ===== --}}
      <div class="gold-panel p-8 relative hidden lg:block">
        <div class="gold-watermark" style="background-image:url('{{ asset('images/goldenpool-logo.jpg') }}')"></div>
        <div class="gold-notch">تسجيل الدخول</div>

        <div class="relative h-full flex flex-col justify-end">
          <div class="text-white/95 font-extrabold text-3xl drop-shadow-sm">GOLDEN POOL</div>
          <div class="text-white/85 font-semibold text-xl -mt-1 drop-shadow-sm">ACADEMY</div>
          <p class="text-white/85 mt-3 text-sm leading-6 max-w-sm">
          GOLDEN POLL ACADEMY 
          </p>
        </div>
      </div>

      {{-- ===== Right: form column ===== --}}
      <div class="px-6 sm:px-10 py-8">

        {{-- Brand + heading --}}
        <div class="flex items-center justify-center gap-3 mb-5">
          <img src="{{ asset('images/goldenpool-logo.jpg') }}" alt="Golden Pool Academy"
               class="h-12 w-12 rounded-xl ring-1 ring-black/10 dark:ring-white/10 object-cover">
          <div class="text-center">
            <div class="text-2xl font-extrabold text-neutral-900 dark:text-neutral-100 tracking-tight">
              LOG<span class="text-amber-500">IN</span>
            </div>
            <div class="text-xs text-neutral-500 dark:text-neutral-400">مرحباً بك — يرجى إدخال بياناتك</div>
          </div>
        </div>

        {{-- Flash / errors --}}
        @if(session('status'))
          <div class="mb-3 rounded-xl border border-amber-400/40 bg-amber-400/10 text-amber-800 dark:text-amber-300 p-3 text-sm">
            {{ session('status') }}
          </div>
        @endif
        @if($errors->any())
          <div class="mb-3 rounded-xl border border-rose-400/40 bg-rose-400/10 text-rose-800 dark:text-rose-300 p-3 text-sm">
            <ul class="list-disc pr-5 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        {{-- ===== Form ===== --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4" novalidate>
          @csrf

          {{-- Email / Username --}}
          <div class="field js-field">
            <span class="field-icon" aria-hidden="true">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 7l9 6 9-6M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7"/>
              </svg>
            </span>
            <input id="email" name="email" type="email" inputmode="email" dir="ltr"
                   class="field-input" placeholder=" "
                   autocomplete="username" required value="{{ old('email') }}">
            <label for="email" class="field-label">البريد الإلكتروني / اسم المستخدم</label>
            @error('email')<span class="mt-1 block text-xs text-rose-600 dark:text-rose-300">{{ $message }}</span>@enderror
          </div>

          {{-- Password --}}
          <div class="field js-field">
            <span class="field-icon" aria-hidden="true">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="11" width="18" height="10" rx="2" ry="2" stroke-width="1.7"></rect>
                <path d="M7 11V8a5 5 0 0110 0v3" stroke-width="1.7" stroke-linecap="round"></path>
              </svg>
            </span>
            <button type="button" class="field-action js-toggle" aria-label="إظهار/إخفاء كلمة المرور" tabindex="-1">
              {{-- eye --}}
              <svg class="w-5 h-5 js-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                <circle cx="12" cy="12" r="3.5" stroke-width="1.7"></circle>
              </svg>
              {{-- eye-off --}}
              <svg class="w-5 h-5 js-eye-off hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 3l18 18M10.58 10.58A3.5 3.5 0 0012 15.5c1.93 0 3.5-1.57 3.5-3.5 0-.42-.08-.82-.22-1.19M9.88 5.07A10.87 10.87 0 0112 5c7 0 11 7 11 7a18.59 18.59 0 01-4.27 4.73M6.18 6.18A18.62 18.62 0 001 12s4 7 11 7a10.9 10.9 0 005.45-1.46"/>
              </svg>
            </button>
            <input id="password" name="password" type="password"
                   class="field-input" placeholder=" "
                   autocomplete="current-password" required>
            <label for="password" class="field-label">كلمة المرور</label>
            @error('password')<span class="mt-1 block text-xs text-rose-600 dark:text-rose-300">{{ $message }}</span>@enderror
            <div id="capsHint" class="hidden mt-1 text-[12px] text-amber-700 dark:text-amber-300">تحذير: زر الحروف الكبيرة (Caps Lock) مفعّل</div>
          </div>

          {{-- Row: remember + forgot --}}
          <div class="flex items-center justify-between text-sm">
            <label class="inline-flex items-center gap-2 select-none">
              <input type="checkbox" name="remember" class="rounded border-neutral-300 dark:border-neutral-700">
              تذكّرني
            </label>
            @if(Route::has('password.request'))
              <a class="text-amber-700 hover:text-amber-800 dark:text-amber-300 dark:hover:text-amber-200 underline underline-offset-4"
                 href="{{ route('password.request') }}">هل نسيت كلمة المرور؟</a>
            @endif
          </div>

          {{-- Submit --}}
          <div class="relative">
            <button type="submit"
                    class="w-full rounded-full px-5 py-3 font-semibold text-white
                           bg-gradient-to-r from-[var(--gold-2)] to-[var(--gold-3)]
                           hover:from-[var(--gold-1)] hover:to-[var(--gold-2)]
                           shadow-[0_10px_30px_-10px_rgba(231,191,72,.55)]
                           focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--gold-2)] focus-visible:ring-offset-2
                           dark:focus-visible:ring-offset-neutral-900
                           transition-transform duration-200 hover:-translate-y-0.5 active:translate-y-0">
              تسجيل الدخول
            </button>
            <span aria-hidden="true"
                  class="pointer-events-none absolute inset-y-0 right-0 w-1/3 translate-x-[130%]
                         bg-white/35 rounded-lg blur-md
                         [mask-image:linear-gradient(to_left,transparent,black)]
                         motion-safe:animate-[sheen_2.2s_ease-in-out_infinite]"></span>
          </div>

          {{-- Optional: register link --}}
          @if(Route::has('register'))
            <p class="text-center text-sm text-neutral-500 dark:text-neutral-400">
              لا تملك حساباً؟ <a href="{{ route('register') }}" class="text-amber-700 dark:text-amber-300 underline underline-offset-4">إنشاء حساب</a>
            </p>
          @endif
        </form>

        <p class="mt-6 text-center text-[11px] text-neutral-400 dark:text-neutral-500">
          © {{ date('Y') }} Golden Pool Academy — جميع الحقوق محفوظة.
        </p>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  (function () {
    // Floating label "filled" state (incl. autofill)
    var inputs = document.querySelectorAll('.js-field input');
    function mark(inp){
      var filled = (inp.value||'').trim().length>0;
      var wrap = inp.closest('.field'); if(wrap) wrap.classList.toggle('filled', filled);
    }
    Array.prototype.forEach.call(inputs, function(inp){
      ['input','change'].forEach(function(ev){ inp.addEventListener(ev, function(){ mark(inp); }); });
      setTimeout(function(){ mark(inp); }, 60); // for autofill
    });

    // Show/Hide password + Caps Lock hint
    var pass = document.getElementById('password');
    var btn  = document.querySelector('.js-toggle');
    var eye  = document.querySelector('.js-eye');
    var off  = document.querySelector('.js-eye-off');
    var caps = document.getElementById('capsHint');

    if(btn && pass){
      btn.addEventListener('click', function(){
        var t = pass.getAttribute('type')==='password' ? 'text' : 'password';
        pass.setAttribute('type', t);
        if(eye && off){ eye.classList.toggle('hidden'); off.classList.toggle('hidden'); }
        pass.focus();
      });
      pass.addEventListener('keydown', function(e){
        if(!caps) return;
        var on = e.getModifierState && e.getModifierState('CapsLock');
        caps.classList.toggle('hidden', !on);
      });
      pass.addEventListener('blur', function(){ if(caps) caps.classList.add('hidden'); });
    }
  })();
</script>
@endpush
