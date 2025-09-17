@extends('layouts.app')
@section('title','تعليق جديد')

@section('content')
  <h1 class="text-xl font-bold mb-4">تعليق جديد</h1>

  <form action="{{ route('comments.store') }}" method="POST"
        class="rounded-2xl border bg-white/60 dark:bg-neutral-900/60 p-5">
    @include('comments._form')
  </form>
@endsection
