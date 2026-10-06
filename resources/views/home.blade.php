@extends('layouts.app')
@section('title', 'Lavéa Parfums — Wear your quiet')
@section('content')
<section class="hero">
  <div class="hero-text">
    <h1>Scent is the<br>first thing<br>they remember.</h1>
    <p>Lavéa is a small-batch perfume house. Every fragrance is blended in 200-bottle runs from natural absolutes, aged for six weeks, and named for the hour it was made for.</p>
    <div class="cta">
      @auth <a class="btn" href="{{ route('collection') }}">Browse the collection</a>
      @else <a class="btn" href="{{ route('register') }}">Create your account</a><a class="pill" href="{{ route('login') }}">Log in</a> @endauth
    </div>
  </div>
  <div class="hero-bottles">
    <div class="hb b1">@include('partials.bottle', ['color' => '#8e5bb0', 'id' => 'h1'])</div>
    <div class="hb b2">@include('partials.bottle', ['color' => '#d9779f', 'id' => 'h2'])</div>
    <div class="hb b3">@include('partials.bottle', ['color' => '#c98a3d', 'id' => 'h3'])</div>
  </div>
</section>

<section class="section">
  <h2>Find your scent family</h2>
  <p class="sub">Tap a family to see how it smells.</p>
  <div class="families">
    @foreach ([
      ['Floral','Violet, rose and iris. Soft, powdery and bright.','#d9779f'],
      ['Woody','Cedar, vetiver and sandalwood. Warm and grounded.','#8a6344'],
      ['Fresh','Sea salt, mint and bergamot. Clean and airy.','#5fa8c4'],
      ['Oriental','Oud, amber and vanilla. Deep and lingering.','#4a2a5e'],
    ] as [$n,$d,$c])
      <button class="family glass" style="--c:{{ $c }}" data-family><span class="dot"></span><h3>{{ $n }}</h3><p>{{ $d }}</p></button>
    @endforeach
  </div>
</section>

<section class="section split">
  <div class="glass about">
    <h2>Made slowly, on purpose</h2>
    <p>We blend by hand in our studio, age every batch for six weeks, and number each bottle. When a batch sells out, it is gone until next season.</p>
  </div>
  <ul class="perks">
    <li class="glass"><b>Layering guide</b><span>Each scent comes with pairings that work together.</span></li>
    <li class="glass"><b>Live stock</b><span>See what is left in every batch, updated by our team.</span></li>
    <li class="glass"><b>Member access</b><span>Create a free account to browse the full collection.</span></li>
  </ul>
</section>
@endsection
