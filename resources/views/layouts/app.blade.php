<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Lavéa Parfums')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="@yield('body')">
<header class="nav">
  <a href="{{ route('home') }}" class="logo">Lavéa</a>
  <nav>
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'on' : '' }}">Home</a>
    @auth
      <a href="{{ route('collection') }}" class="{{ request()->routeIs('collection') ? 'on' : '' }}">Collection</a>
      <a href="{{ route('stock') }}" class="{{ request()->routeIs('stock') ? 'on' : '' }}">Stock</a>
      <form method="POST" action="{{ route('logout') }}" class="inline">@csrf
        <button class="pill ghost">Log out ({{ auth()->user()->username }})</button></form>
    @else
      <a href="{{ route('login') }}">Log in</a>
      <a href="{{ route('register') }}" class="pill">Sign up</a>
    @endauth
  </nav>
</header>
@if (session('success'))<div class="toast" role="status">{{ session('success') }}</div>@endif
<main>@yield('content')</main>
<footer class="foot">© {{ date('Y') }} Lavéa Parfums · Small-batch fragrance, made slowly.</footer>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
