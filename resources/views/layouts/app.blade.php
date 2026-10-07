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
<body class="@yield('body') {{ auth()->check() && auth()->user()->isAdmin() ? 'admin-mode' : '' }}">
<header class="nav">
  <a href="{{ route('home') }}" class="logo">Lavéa</a>@if (auth()->check() && auth()->user()->isAdmin())<span class="badge">Admin</span>@endif
  <nav>
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'on' : '' }}">Home</a>
    @auth
      @if (auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">Dashboard</a>
        <a href="{{ route('stock') }}" class="{{ request()->routeIs('stock*') ? 'on' : '' }}">Inventory</a>
        <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'on' : '' }}">Orders</a>
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'on' : '' }}">Users</a>
        <a href="{{ route('collection') }}" class="{{ request()->routeIs('collection') ? 'on' : '' }}">Shop view</a>
      @else
        <a href="{{ route('collection') }}" class="{{ request()->routeIs('collection') ? 'on' : '' }}">Shop</a>
        <a href="{{ route('cart') }}" class="{{ request()->routeIs('cart') ? 'on' : '' }}">Cart ({{ collect(session('cart', []))->sum() }})</a>
        <a href="{{ route('orders') }}" class="{{ request()->routeIs('orders') ? 'on' : '' }}">My orders</a>
      @endif
      <form method="POST" action="{{ route('logout') }}" class="inline">@csrf
        <button class="pill ghost">Log out ({{ auth()->user()->username }})</button></form>
    @else
      <a href="{{ route('login') }}">Log in</a>
      <a href="{{ route('register') }}" class="pill">Sign up</a>
    @endauth
  </nav>
</header>
@if (session('error'))<div class="toast bad" role="alert">{{ session('error') }}</div>@endif
@if (session('success'))<div class="toast" role="status">{{ session('success') }}</div>@endif
<main>@yield('content')</main>
<footer class="foot">© {{ date('Y') }} Lavéa Parfums · Small-batch fragrance, made slowly.</footer>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
