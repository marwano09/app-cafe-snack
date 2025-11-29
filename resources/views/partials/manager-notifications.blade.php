@php
  $unread = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
@endphp

<div x-data="{ open:false }" class="relative">
  <button @click="open=!open"
          class="relative rounded-xl px-3 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800">
    <span class="text-lg">🔔</span>
    @if($unread > 0)
      <span class="absolute -top-1 -right-1 text-[10px] bg-red-600 text-white rounded-full px-1.5 py-0.5">
        {{ $unread }}
      </span>
    @endif
  </button>

  <div x-cloak x-show="open" @click.outside="open=false"
       class="absolute right-0 mt-2 w-80 rounded-2xl border border-neutral-200 dark:border-neutral-800
              bg-white dark:bg-neutral-900 shadow-xl overflow-hidden z-50">
    <div class="p-3 text-sm font-semibold">الإشعارات</div>
    <div class="max-h-96 overflow-y-auto divide-y divide-neutral-100 dark:divide-neutral-800">
      @forelse(auth()->user()->notifications()->latest()->limit(10)->get() as $n)
        <a href="{{ route('manager.notifications.read', $n->id) }}"
           class="block px-3 py-2 text-sm hover:bg-neutral-50 dark:hover:bg-neutral-800
                  {{ is_null($n->read_at) ? 'bg-amber-50/50 dark:bg-amber-900/10' : '' }}">
          <div class="font-medium">{{ $n->data['message'] ?? 'إشعار' }}</div>
          <div class="text-xs opacity-70 mt-0.5">
            DH {{ number_format((float)($n->data['total'] ?? 0),2) }}
            — #{{ $n->data['order_id'] ?? '' }}
            — {{ $n->created_at->diffForHumans() }}
          </div>
        </a>
      @empty
        <div class="px-3 py-6 text-center text-sm opacity-60">لا إشعارات</div>
      @endforelse
    </div>
    <div class="p-2 text-center">
      <a href="{{ route('manager.notifications.index') }}" class="text-xs underline">كل الإشعارات</a>
    </div>
  </div>
</div>
