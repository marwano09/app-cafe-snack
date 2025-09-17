@extends('layouts.app')
@section('title','تعديل صنف')

@section('content')
<form action="{{ route('menu.update',$item) }}" method="post" enctype="multipart/form-data" class="space-y-4">
  @csrf @method('PUT')

  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm mb-1">الاسم</label>
      <input name="name" value="{{ old('name',$item->name) }}" class="w-full input" required>
      @error('name')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="block text-sm mb-1">السعر (DH)</label>
      <input type="number" step="0.01" name="price" value="{{ old('price',$item->price) }}" class="w-full input" required>
      @error('price')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="block text-sm mb-1">الفئة</label>
      <select name="category_id" class="w-full input" required>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected(old('category_id',$item->category_id)==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
      @error('category_id')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="block text-sm mb-1">متاح؟</label>
      <select name="is_available" class="w-full input">
        <option value="1" @selected(old('is_available',$item->is_available)=='1')>نعم</option>
        <option value="0" @selected(old('is_available',$item->is_available)=='0')>لا</option>
      </select>
      @error('is_available')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="md:col-span-2">
      <label class="block text-sm mb-1">الوصف</label>
      <textarea name="description" rows="3" class="w-full input">{{ old('description',$item->description) }}</textarea>
      @error('description')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>

    {{-- Current + preview --}}
    <div class="md:col-span-2 space-y-2">
      <label class="block text-sm mb-1">الصورة الحالية</label>
      <div class="flex items-center gap-4">
        <img id="currentImage" src="{{ $item->image_url }}" class="h-24 w-24 object-cover rounded-lg ring-1 ring-black/10" alt="">
        <label class="inline-flex items-center gap-2 text-sm">
          <input type="checkbox" name="remove_image" value="1">
          <span>حذف الصورة</span>
        </label>
      </div>
    </div>

    <div class="md:col-span-2">
      <label class="block text-sm mb-1">تغيير الصورة (اختياري)</label>
      <input id="imageInput" type="file" name="image" accept="image/*" class="w-full input">
      @error('image')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
    </div>
  </div>

  <button class="btn btn-primary mt-4">تحديث</button>
</form>
@endsection

@push('scripts')
<script>
  document.getElementById('imageInput')?.addEventListener('change', (e) => {
    const [file] = e.target.files || [];
    if (!file) return;
    document.getElementById('currentImage').src = URL.createObjectURL(file);
  });
</script>
@endpush
