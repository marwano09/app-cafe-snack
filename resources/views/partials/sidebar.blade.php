{{-- resources/views/partials/sidebar.blade.php --}}
@php
  // helper: mark link active
  $isActive = fn ($patterns) => collect((array)$patterns)->some(
      fn($p) => request()->routeIs($p)
  );

  // style tokens
  $base   = 'flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition';
  $idle   = 'hover:bg-neutral-100 dark:hover:bg-neutral-800';
  $active = 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 shadow-sm';

  // counts (optional) – default to 0 if not passed by controller
  $ordersToday = (int)($counts['orders_today'] ?? 0);
  $kitchenOpen = (int)($counts['kitchen_open'] ?? 0);

  // brand image fallback
  $brand = null;
  if (file_exists(public_path('images/goldenpool-logo.jpg')))      $brand = 'public/images/goldenpool-logo.jpg';
  elseif (file_exists(public_path('images/goldenpool-logo.jpg')))  $brand = 'public/images/goldenpool-logo.jpg';
  elseif (file_exists(public_path('images/goldenpool-logo.jpg')))   $brand = 'public/images/goldenpool-logo.jpg';
@endphp

{{-- mobile backdrop --}}
<div id="sidebarBackdrop"
     class="fixed inset-0 bg-black/40 hidden lg:hidden"
     aria-hidden="true"></div>

<aside id="appSidebar"
       class="fixed lg:static inset-y-0 right-0 z-40 w-[18rem] max-w-[85vw]
              translate-x-full lg:translate-x-0
              border-l border-neutral-200 dark:border-neutral-800
              bg-white dark:bg-neutral-950
              transition-transform duration-200 ease-out
              shadow-xl lg:shadow-none">
  <div class="h-full flex flex-col">

    {{-- header / brand --}}
    <div class="px-4 py-4 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between">
      <div class="flex items-center gap-3 min-w-0">
        @if($brand)
         <img src="{{ asset('images/goldenpool-logo.jpg') }}" alt="Golden Pool Academy" class="h-8 w-auto">

        @else
          <div class="h-10 w-10 rounded-lg grid place-items-center bg-black text-white text-sm font-bold">C&S</div>
        @endif
        <div class="leading-tight min-w-0">
          <div class="font-semibold truncate">Golden Pool Academy</div>
          <div class="text-[11px] opacity-60 truncate">{{ auth()->user()->name }}</div>
        </div>
      </div>

      <button id="closeSidebar"
              class="lg:hidden size-9 grid place-items-center rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800"
              aria-label="إغلاق القائمة الجانبية">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    {{-- nav --}}
    <nav class="p-3 overflow-y-auto flex-1 space-y-1" aria-label="Main">
      {{-- Section: general --}}
      <div class="px-2 text-[11px] uppercase tracking-wider opacity-60">عام</div>
<a href="{{ route('history.month') }}" class="{{ $base }} {{ $isActive(['history.*']) ? $active : $idle }}">
  <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h8M3 8h18M4 11h16M4 15h10M4 19h8"/>
  </svg>
  <span>الأرشيف (تقويم)</span>
</a>

      <a href="{{ route('dashboard') }}"
         class="{{ $base }} {{ $isActive('dashboard') ? $active : $idle }}">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M3 12l9-9 9 9M4.5 10.5V21h15V10.5"/>
        </svg>
        <span>لوحة التحكم</span>
      </a>

      @role('waiter|manager')
      <a href="{{ route('orders.create') }}"
         class="{{ $base }} {{ $isActive('orders.create') ? $active : $idle }}">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6"/>
        </svg>
        <span class="grow">الطلبات</span>
        @if($ordersToday > 0)
          <span class="text-[10px] px-2 py-0.5 rounded-full bg-neutral-900 text-white dark:bg-white dark:text-neutral-900">{{ $ordersToday }}</span>
        @endif
      </a>
      @endrole

      @role('kitchen|bar|manager')
      <a href="{{ route('kitchen.index') }}"
         class="{{ $base }} {{ $isActive('kitchen.index') ? $active : $idle }}">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M7 6v12m10-12v12M4 18h16"/>
        </svg>
        <span class="grow">المطبخ/البار</span>
        @if($kitchenOpen > 0)
          <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-600 text-white">{{ $kitchenOpen }}</span>
        @endif
      </a>
      @endrole

      {{-- Section: management --}}
      @role('manager')
      <div class="pt-1">
        <button type="button"
                class="w-full {{ $base }} {{ $idle }} justify-between"
                data-accordion-toggle="#mgmt"
                aria-controls="mgmt"
                aria-expanded="false">
          <span class="flex items-center gap-3">
            <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/>
            </svg>
            <span>الإدارة</span>
          </span>
          <svg class="h-4 w-4 opacity-70 transition" data-chevron viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <a href="{{ route('comments.index') }}"
   class="{{ $base }} {{ $isActive('comments.index') ? $active : $idle }}">
  <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M8 10h8M8 14h6M4 6h16v12H4z"/>
  </svg>
  <span>التعليقات</span>
</a><a href="{{ route('stock.items.index') }}"
   class="{{ $base }} {{ $isActive(['stock.items.*','stock.purchases.*','stock.adjustments.*','stock.movements.*']) ? $active : $idle }}">
  📦 <span>إدارة المخزون</span>
</a>


        <a href="{{ route('orders.index') }}"
   class="{{ $base }} {{ $isActive('orders.index') ? $active : $idle }}">
  <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M6 7v10m12-10v10M3 17h18"/>
  </svg>
  <span>كل الطلبات</span>
</a>

<a href="{{ route('users.index') }}"
   class="{{ $base }} {{ $isActive(['users.index','users.create','users.edit']) ? $active : $idle }}">
  <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5 1.343 3.5 3 3.5zM8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11zm8 2c-2.21 0-4 2.239-4 5h2c0-2.209 1.343-3 2-3s2 .791 2 3h2c0-2.761-1.79-5-4-5zM8 13c-2.21 0-4 2.239-4 5h2c0-2.209 1.343-3 2-3s2 .791 2 3h2c0-2.761-1.79-5-4-5z"/>
  </svg>
  <span>المستخدمون</span>
</a>

<a href="{{ route('categories.index') }}"
   class="{{ $base }} {{ $isActive(['categories.index','categories.create','categories.edit']) ? $active : $idle }}">
  <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M4 7h16M4 12h16M4 17h10"/>
  </svg>
  <span>الفئات</span>
</a>

        <div id="mgmt" class="hidden space-y-1 pl-3 mt-1" role="region" aria-label="روابط الإدارة">
          <a href="{{ route('manager.index') }}"
             class="{{ $base }} {{ $isActive('manager.index') ? $active : $idle }}">
            <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12h18M3 6h18M3 18h18"/>
            </svg>
            <span>إحصاءات المدير</span>
          </a>

          <a href="{{ route('menu.index') }}"
             class="{{ $base }} {{ $isActive(['menu.index','menu.create','menu.edit']) ? $active : $idle }}">
            <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h10"/>
            </svg>
            <span>إدارة القائمة</span>
          </a>
        </div>
      </div>
      @endrole

      @if(Route::has('profile.edit'))
      <a href="{{ route('profile.edit') }}"
         class="{{ $base }} {{ $isActive('profile.edit') ? $active : $idle }}">
        <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0"/>
        </svg>
        <span>الملف الشخصي</span>
      </a>
      @endif
    </nav>

    {{-- footer / logout --}}
    <div class="px-4 py-3 border-t border-neutral-200 dark:border-neutral-800">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="w-full {{ $base }} justify-center bg-gradient-to-tr from-neutral-900 to-neutral-700 text-white
                       hover:opacity-95 dark:from-white dark:to-neutral-200 dark:text-neutral-900">
          🚪 تسجيل الخروج
        </button>
      </form>
      <div class="mt-3 text-[11px] text-center opacity-60">© {{ now()->year }} — Golden Pool Academy</div>
    </div>
  </div>
</aside>
