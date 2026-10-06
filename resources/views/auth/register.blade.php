@extends('layouts.app')
@section('title', 'Sign up — Lavéa')
@section('body', 'auth-page')
@section('content')
<div class="auth"><div class="auth-art">@include('partials.bottle', ['color' => '#8e5bb0'])</div><div class="auth-form">
  <a class="pill small" href="{{ route('login') }}">Already have an account? Log in</a>
  <h1>Create your account</h1>
  <form method="POST" action="{{ route('register') }}" novalidate data-validate>
    @csrf
    <div class="field"><input name="name" value="{{ old('name') }}" placeholder="Full name" autocomplete="name" required minlength="2">
      @error('name')<small class="err">{{ $message }}</small>@enderror</div>
    <div class="field"><input name="username" value="{{ old('username') }}" placeholder="Username" autocomplete="username" required minlength="3" maxlength="20" pattern="[A-Za-z0-9_\-]+">
      @error('username')<small class="err">{{ $message }}</small>@enderror</div>
    <div class="field"><input type="email" name="email" value="{{ old('email') }}" placeholder="Email" autocomplete="email" required>
      @error('email')<small class="err">{{ $message }}</small>@enderror</div>
    <div class="field"><input type="password" name="password" id="pw" placeholder="Password" autocomplete="new-password" required minlength="8">
      <button type="button" class="eye" data-eye aria-label="Show password">Show</button>
      <div class="meter"><i id="meter"></i></div><small class="hint" id="meter-txt">Use 8+ characters with upper, lower case and a number.</small>
      @error('password')<small class="err">{{ $message }}</small>@enderror</div>
    <div class="field"><input type="password" name="password_confirmation" id="pw2" placeholder="Confirm password" autocomplete="new-password" required></div>
    <label class="check"><input type="checkbox" name="terms" required> I agree to the terms and privacy policy</label>
    @error('terms')<small class="err">{{ $message }}</small>@enderror
    <button class="btn full">Create account</button>
  </form>
</div></div>
@endsection
