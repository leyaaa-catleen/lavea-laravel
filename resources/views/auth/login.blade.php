@extends('layouts.app')
@section('title', 'Log in — Lavéa')
@section('body', 'auth-page')
@section('content')
<div class="auth"><div class="auth-art">@include('partials.bottle', ['color' => '#8e5bb0'])</div><div class="auth-form">
  <a class="pill small" href="{{ route('register') }}">Don't have an account? Sign up</a>
  <h1>Welcome back</h1>
  <form method="POST" action="{{ route('login') }}" novalidate data-validate>
    @csrf
    <div class="field">
      <input type="text" name="login" value="{{ old('login') }}" placeholder="Email or Username" autocomplete="username" required autofocus>
      @error('login')<small class="err">{{ $message }}</small>@enderror
    </div>
    <div class="field">
      <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
      <button type="button" class="eye" data-eye aria-label="Show password">Show</button>
      @error('password')<small class="err">{{ $message }}</small>@enderror
    </div>
    <label class="check"><input type="checkbox" name="remember"> Keep me logged in</label>
    <button class="btn full">Log in</button>
  </form>
</div></div>
@endsection
