<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Cafe & Snack')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-dvh bg-neutral-50 dark:bg-neutral-950 text-neutral-900 dark:text-neutral-100">
  <main class="container mx-auto py-8 px-4">
    @yield('content')
  </main>
  <footer class="text-center text-xs opacity-70 py-4">
    M AND Y {{ now()->year }}
  </footer>
</body>
</html>
