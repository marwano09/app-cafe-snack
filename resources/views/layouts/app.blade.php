{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','لوحة التحكم')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-dvh bg-neutral-50 dark:bg-neutral-900 text-neutral-900 dark:text-neutral-100">

  {{-- App shell --}}
  <div class="min-h-dvh grid lg:grid-cols-[280px_1fr]">
    {{-- Sidebar (drawer on mobile, static on lg+) --}}
    @include('partials.sidebar')

    {{-- Main column --}}
    <div class="flex flex-col min-w-0">
      {{-- Top bar (has hamburger to open sidebar on mobile) --}}
      @include('partials.topbar')
@push('scripts')
<script>
(function(){
  const $  = (s, r=document) => r.querySelector(s);
  const sidebar  = $('#appSidebar');
  const backdrop = $('#sidebarBackdrop');
  const openBtn  = $('#openSidebar');
  const closeBtn = $('#closeSidebar');

  const open = () => {
    sidebar?.classList.remove('translate-x-full');
    backdrop?.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    openBtn?.setAttribute('aria-expanded', 'true');
  };
  const close = () => {
    sidebar?.classList.add('translate-x-full');
    backdrop?.classList.add('hidden');
    document.body.style.overflow = '';
    openBtn?.setAttribute('aria-expanded', 'false');
  };

  openBtn?.addEventListener('click', open);
  closeBtn?.addEventListener('click', close);
  backdrop?.addEventListener('click', close);
  window.addEventListener('keydown', e => e.key === 'Escape' && close());

  // Accordion (your “الإدارة” toggle)
  document.querySelectorAll('[data-accordion-toggle]').forEach(btn => {
    const target = document.querySelector(btn.getAttribute('data-accordion-toggle'));
    const chev   = btn.querySelector('[data-chevron]');
    if (!target) return;
    btn.addEventListener('click', () => {
      const isOpen = !target.classList.contains('hidden');
      target.classList.toggle('hidden', isOpen);
      btn.setAttribute('aria-expanded', String(!isOpen));
      if (chev) chev.style.transform = isOpen ? '' : 'rotate(180deg)';
    });
  });
})();
</script>
@endpush

      <main class="p-4 max-w-7xl mx-auto w-full">
        @include('partials.flash')
        @yield('content')
      </main>
    </div>
  </div>

  {{-- Global footer --}}
  <footer class="text-center text-xs opacity-70 py-4">
    M AND Y {{ now()->year }}
  </footer>

  {{-- ===== Script stack (REQUIRED for sidebar open/close + accordion) ===== --}}
  @stack('scripts')

  {{-- You can keep the JS here if you prefer not to push from pages --}}
  @push('scripts')
  <script>
    (function(){
      const $  = (s, r=document) => r.querySelector(s);
      const $$ = (s, r=document) => Array.from(r.querySelectorAll(s));

      const sidebar  = $('#appSidebar');
      const backdrop = $('#sidebarBackdrop');
      const openBtn  = $('#openSidebar');
      const closeBtn = $('#closeSidebar');

      const open = () => {
        sidebar?.classList.remove('translate-x-full');
        backdrop?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      };
      const close = () => {
        sidebar?.classList.add('translate-x-full');
        backdrop?.classList.add('hidden');
        document.body.style.overflow = '';
      };

      openBtn?.addEventListener('click', open);
      closeBtn?.addEventListener('click', close);
      backdrop?.addEventListener('click', close);
      window.addEventListener('keydown', (e) => e.key === 'Escape' && close());

      // Collapsible sections (e.g., الإدارة)
      $$('[data-accordion-toggle]').forEach(btn => {
        const targetSel = btn.getAttribute('data-accordion-toggle');
        const target    = $(targetSel);
        const chev      = btn.querySelector('[data-chevron]');
        if (!target) return;

        btn.addEventListener('click', () => {
          const isOpen = !target.classList.contains('hidden');
          target.classList.toggle('hidden', isOpen);
          btn.setAttribute('aria-expanded', String(!isOpen));
          if (chev) chev.style.transform = isOpen ? '' : 'rotate(180deg)';
        });
      });
    })();
  </script>
  @endpush
</body>
</html>
