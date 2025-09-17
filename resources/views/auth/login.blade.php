@extends('layouts.guest')
@section('title','Golden Pool Academy – تسجيل الدخول')

@section('content')
<div class="min-h-[90dvh] grid md:grid-cols-2 rounded-3xl overflow-hidden shadow-2xl bg-white dark:bg-neutral-900">
  
  <!-- العمود الأيسر: الفورم -->
  <div class="p-8 flex flex-col justify-center">
    <!-- الشعار -->
    <div class="flex justify-center mb-6">
      <img src="{{ asset('images/goldenpool-logo.jpg') }}" 
           alt="Golden Pool Academy" 
           class="h-20 w-auto drop-shadow-lg">
    </div>

    <h1 class="text-2xl font-bold text-center mb-2">تسجيل الدخول</h1>
    <p class="text-center text-neutral-500 mb-6">أدخل بريدك وكلمة المرور للولوج إلى النظام</p>

    @if(session('status'))
      <div class="mb-3 rounded-xl border border-cyan-400/30 bg-cyan-400/10 text-cyan-700 p-3 text-sm">
        {{ session('status') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-3 rounded-xl border border-red-400/30 bg-red-400/10 text-red-700 p-3 text-sm">
        <ul class="list-disc pr-5 space-y-1">
          @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
      @csrf
      <!-- البريد -->
      <div>
        <label class="block text-sm mb-1">البريد الإلكتروني</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full rounded-xl border border-neutral-300 px-3 py-2 focus:ring-2 focus:ring-cyan-500 outline-none">
      </div>

      <!-- كلمة المرور -->
      <div>
        <div class="flex items-center justify-between mb-1">
          <label class="block text-sm">كلمة المرور</label>
          @if(Route::has('password.request'))
            <a class="text-xs underline text-neutral-500 hover:text-neutral-800" href="{{ route('password.request') }}">
              هل نسيت كلمة المرور؟
            </a>
          @endif
        </div>
        <input type="password" name="password" required
               class="w-full rounded-xl border border-neutral-300 px-3 py-2 focus:ring-2 focus:ring-cyan-500 outline-none">
      </div>

      <label class="inline-flex items-center gap-2 text-sm">
        <input type="checkbox" name="remember" class="rounded border-neutral-300">
        تذكرني
      </label>

      <button type="submit"
              class="w-full rounded-xl px-4 py-2 font-semibold text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 transition">
        تسجيل الدخول
      </button>
    </form>
  </div>

  <!-- العمود الأيمن: خلفية + اللوجو -->
  <div class="hidden md:flex flex-col justify-center items-center bg-gradient-to-br from-yellow-700 to-yellow-900 text-white p-8 relative">
    
    <!-- صورة الخلفية (اللوجو) -->
    <img src="{{ asset('images/goldenpool-logo.jpg') }}" 
         alt="Golden Pool Academy Logo" 
         class="absolute opacity-10 inset-0 w-full h-full object-contain pointer-events-none">

    <div class="relative z-10 text-center">
      <h2 class="text-3xl font-bold mb-4">Golden Pool Academy</h2>
      <p class="mb-6 max-w-sm">استمتع بوقتك مع البلياردو، القهوة، والمأكولات. انضم إلينا لتجربة ممتعة!</p>
      
      @if(Route::has('register'))
        <a href="{{ route('register') }}"
           class="px-6 py-2 rounded-xl font-semibold bg-white text-yellow-800 hover:bg-neutral-100 transition">
           إنشاء حساب جديد
        </a>
      @endif
    </div>
  </div>
</div>
@endsection
