@extends('layouts.app')
@section('title','تعديل تعليق')

@section('content')
  <h1 class="text-xl font-bold mb-4">تعديل تعليق</h1>

  <form action="{{ route('comments.update',$comment) }}" method="POST"
        class="rounded-2xl border bg-white/60 dark:bg-neutral-900/60 p-5">
    @method('PUT')
    @include('comments._form', ['comment'=>$comment])
  </form>
@endsection
