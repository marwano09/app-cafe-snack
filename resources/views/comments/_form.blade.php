{{-- shared form for create & edit --}}
@csrf
<div class="space-y-3">
  <label class="block text-sm">
    <span class="mb-1 block">نص التعليق</span>
    <textarea name="body" required rows="4"
      class="w-full rounded-xl border bg-white/70 dark:bg-neutral-900/70 p-3"
      placeholder="اكتب تعليقك هنا...">{{ old('body', $comment->body ?? '') }}</textarea>
  </label>

  <div class="flex items-center gap-2">
    <a href="{{ route('comments.index') }}" class="px-3 py-2 rounded-xl bg-neutral-200 dark:bg-neutral-800">
      رجوع
    </a>

    <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">
      حفظ
    </button>
  </div>
</div>
