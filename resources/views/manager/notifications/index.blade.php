@extends('layouts.app')
@section('title','الإشعارات')

@section('content')
<div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
  <div class="p-4 text-lg font-bold">الإشعارات</div>
  <div class="divide-y divide-neutral-100 dark:divide-neutral-800">
    @forelse($notifications as $n)
      <a href="{{ route('manager.notifications.read', $n->id) }}"
         class="block px-4 py-3 hover:bg-neutral-50 dark:hover:bg-neutral-800
                {{ is_null($n->read_at) ? 'bg-amber-50/50 dark:bg-amber-900/10' : '' }}">
        <div class="flex items-center justify-between">
          <div class="font-medium">{{ $n->data['message'] ?? 'إشعار' }}</div>
          <div class="text-xs opacity-70">{{ $n->created_at->diffForHumans() }}</div>
        </div>
        <div class="text-xs opacity-70 mt-1">
          طلب #{{ $n->data['order_id'] ?? '' }} —
          DH {{ number_format((float)($n->data['total'] ?? 0),2) }}
        </div>
      </a>
    @empty
      <div class="px-4 py-10 text-center opacity-60">لا توجد إشعارات.</div>
    @endforelse
  </div>

  <div class="p-3">{{ $notifications->links() }}</div>
</div>
@endsection
