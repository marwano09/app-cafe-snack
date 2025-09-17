@if(session('ok'))
  <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-900 px-4 py-3 text-sm">
    {{ session('ok') }}
  </div>
@endif

@if ($errors->any())
  <div class="mb-4 rounded-xl border border-red-200 bg-red-50 text-red-900 px-4 py-3 text-sm">
    <ul class="list-disc pe-5 space-y-1">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
