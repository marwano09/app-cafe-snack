@extends('layouts.app')
@section('title','المستخدمون')

@section('content')
{{-- Header --}}
<div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
  <div>
    <h1 class="text-2xl font-semibold tracking-tight text-neutral-900 dark:text-neutral-100">المستخدمون</h1>
    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-0.5">إدارة الحسابات والأدوار وصلاحيات الوصول.</p>
  </div>
  <a href="{{ route('users.create') }}"
     class="inline-flex items-center gap-2 rounded-xl bg-neutral-900 text-white px-4 py-2 text-sm hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200 transition">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 6v12m6-6H6"/></svg>
    مستخدم جديد
  </a>
</div>

{{-- Alerts --}}
@if(session('ok'))
  <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-900 p-3 text-sm">
    {{ session('ok') }}
  </div>
@endif
@if($errors->any())
  <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-900 p-3 text-sm space-y-1">
    @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
  </div>
@endif

{{-- (Optional) Filters – purely UI; remove if you don’t need --}}
<form method="GET" action="{{ route('users.index') }}" class="mb-5 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4">
  <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
    <div class="flex-1">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث بالاسم أو البريد..."
             class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
    </div>
    <div class="flex items-center gap-2">
      <button class="inline-flex items-center gap-2 rounded-xl border px-4 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">
        <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M21 21l-5.2-5.2M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/></svg>
        تصفية
      </button>
      @if(request()->hasAny(['q','role','sort']))
        <a href="{{ route('users.index') }}" class="text-xs text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200">إعادة ضبط</a>
      @endif
    </div>
  </div>
</form>

{{-- Grid --}}
@if($users->count())
  <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    @foreach($users as $u)
      @php
        $name  = $u->name ?? '—';
        $usern = $u->username ?? '—';
        $email = $u->email ?? '—';
        $active = isset($u->is_active) ? (bool)$u->is_active : true;
      @endphp

      <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-start gap-3">
          {{-- avatar (icon) --}}
          <div class="shrink-0 w-12 h-12 rounded-xl bg-neutral-100 dark:bg-neutral-800 grid place-items-center ring-1 ring-black/5 dark:ring-white/10">
            <svg class="w-6 h-6 text-neutral-500 dark:text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/>
            </svg>
          </div>

          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
              <div class="min-w-0">
                <div class="font-semibold truncate">{{ $name }}</div>
                <div class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                  {{ $usern }} • <span dir="ltr">{{ $email }}</span>
                </div>
              </div>

              <span class="text-[11px] px-2 py-0.5 rounded-full border shrink-0
                {{ $active
                    ? 'border-emerald-300 text-emerald-700 dark:border-emerald-700 dark:text-emerald-300'
                    : 'border-rose-300 text-rose-700 dark:border-rose-700 dark:text-rose-300' }}">
                {{ $active ? 'نشط' : 'موقوف' }}
              </span>
            </div>

            {{-- roles --}}
            <div class="mt-2 flex flex-wrap gap-1.5">
              @if(!empty($u->roles) && $u->roles->count())
                @foreach($u->roles as $r)
                  <span class="px-2 py-0.5 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-[11px]">{{ $r->name }}</span>
                @endforeach
              @else
                <span class="px-2 py-0.5 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-[11px]">بدون دور</span>
              @endif
            </div>

            {{-- meta --}}
            @if(!empty($u->created_at))
              <div class="mt-2 text-[11px] text-neutral-500 dark:text-neutral-400">
                أُضيف: {{ \Illuminate\Support\Carbon::parse($u->created_at)->format('Y-m-d H:i') }}
              </div>
            @endif

            {{-- actions --}}
            <div class="mt-3 flex flex-wrap items-center gap-2">
              <a href="{{ route('users.edit', $u) }}"
                 class="inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs hover:bg-neutral-50 dark:hover:bg-neutral-800">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5Z"/></svg>
                تعديل
              </a>

              <button type="button" data-copy="{{ $email }}"
                      class="copy-btn inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs hover:bg-neutral-50 dark:hover:bg-neutral-800">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 9V7a2 2 0 0 1 2-2h7M9 9h6a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2Z"/></svg>
                نسخ البريد
              </button>

              <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('حذف المستخدم؟')" class="inline">
                @csrf @method('DELETE')
                <button class="inline-flex items-center gap-1.5 rounded-lg border border-rose-300 text-rose-700 px-3 py-1.5 text-xs hover:bg-rose-50 dark:hover:bg-rose-950/30">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 7h16M6 7v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
                  حذف
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@else
  <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-8 text-center text-neutral-500 dark:text-neutral-400">
    لا يوجد مستخدمون.
  </div>
@endif

{{-- Pagination --}}
<div class="mt-6">
  {{ $users->withQueryString()->links() }}
</div>
@endsection

@push('scripts')
<script>
  // Front-end only: copy email (doesn't touch backend)
  (function(){
    var btns = document.querySelectorAll('.copy-btn');
    for (var i=0;i<btns.length;i++) {
      btns[i].addEventListener('click', function(){
        var val = this.getAttribute('data-copy') || '';
        if (!val) return;
        navigator.clipboard.writeText(val).then(()=>{
          var old = this.textContent.trim();
          this.textContent = 'تم النسخ ✓';
          setTimeout(()=>{ this.textContent = old; }, 1200);
        });
      });
    }
  })();
</script>
@endpush
