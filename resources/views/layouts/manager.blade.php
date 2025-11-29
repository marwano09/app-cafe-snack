@php
    $unread = auth()->user()?->unreadNotifications()->take(10)->get() ?? collect();
@endphp

<div class="relative">
  <button id="notif-btn" class="relative">
    🔔
    @if($unread->count() > 0)
      <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full px-1">
        {{ $unread->count() }}
      </span>
    @endif
  </button>

  <div id="notif-menu" class="hidden absolute right-0 mt-2 w-80 bg-white shadow-xl rounded-xl overflow-hidden">
    @forelse($unread as $n)
      <a href="{{ route('manager.notifications.read', $n->id) }}"
         class="block px-4 py-3 hover:bg-gray-50">
        <div class="text-sm font-semibold">طلب مؤكد</div>
        <div class="text-xs text-gray-600">
          {{ data_get($n->data, 'message') }}
          — المجموع: {{ number_format(data_get($n->data, 'total', 0), 2) }} درهم
        </div>
        <div class="text-[10px] text-gray-400 mt-1">{{ $n->created_at->diffForHumans() }}</div>
      </a>
    @empty
      <div class="px-4 py-3 text-sm text-gray-500">لا توجد إشعارات</div>
    @endforelse

    <a href="{{ route('manager.notifications.index') }}" class="block text-center text-xs py-2 border-t hover:bg-gray-50">
      كل الإشعارات
    </a>
  </div>
</div>

<script>
document.getElementById('notif-btn')?.addEventListener('click', () => {
  const menu = document.getElementById('notif-menu');
  if (menu) menu.classList.toggle('hidden');
});
</script>
