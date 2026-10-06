@extends('layouts.app')
@section('title', 'Collection — Lavéa')
@section('content')
<section class="section">
  <h1 class="page-h">The collection</h1>
  <p class="sub">Hello {{ auth()->user()->name }}. Filter by family, then tap a bottle to see its notes.</p>
  <div class="chips">
    <button class="chip on" data-filter="all">All</button>
    @foreach (\App\Models\Perfume::CATEGORIES as $c)<button class="chip" data-filter="{{ $c }}">{{ $c }}</button>@endforeach
  </div>
  <div class="grid">
    @forelse ($perfumes as $p)
    <article class="card tilt" style="--c:{{ $p->color }}" data-cat="{{ $p->category }}" data-name="{{ $p->name }}" data-notes="{{ $p->notes }}" data-price="{{ $p->peso }}" data-stock="{{ $p->stock }}" tabindex="0">
      <div class="arch">@include('partials.bottle', ['color' => $p->color])</div>
      <h3>{{ $p->name }}</h3><span class="tag">{{ $p->category }}</span><b>{{ $p->peso }}</b>
    </article>
    @empty
      <p class="empty">No perfumes yet. Add your first one on the <a href="{{ route('stock') }}">Stock</a> page.</p>
    @endforelse
  </div>
</section>
<div class="modal" id="pm" hidden><div class="glass box">
  <button class="x" data-close aria-label="Close">×</button>
  <div id="pm-bottle"></div><h2 id="pm-name"></h2><p id="pm-notes"></p>
  <p><b id="pm-price"></b> · <span id="pm-stock"></span></p>
</div></div>
@endsection
