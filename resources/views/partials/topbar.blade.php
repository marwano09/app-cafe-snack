<header class="sticky top-0 z-30 bg-white/70 dark:bg-neutral-900/70 backdrop-blur border-b border-neutral-200/60 dark:border-neutral-800/70">
  <div class="h-14 px-3 lg:px-4 flex items-center justify-between">
    <div class="flex items-center gap-2">
      {{-- Mobile hamburger --}}
      <button id="openSidebar"
              class="lg:hidden h-10 w-10 grid place-items-center rounded-xl border border-neutral-300/60 dark:border-neutral-700/70 hover:bg-neutral-100 dark:hover:bg-neutral-800"
              aria-label="Open menu" aria-controls="appSidebar" aria-expanded="false">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <div class="font-semibold truncate">
        {{ __('مرحباً') }}, {{ auth()->user()->name }}
      </div>
    </div>
  </div>
</header>
