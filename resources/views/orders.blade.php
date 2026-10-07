@extends('layouts.app')
@section('title', 'My orders — Lavéa')
@section('content')
<section class="section">
  <h1 class="page-h">My orders</h1>
  <p class="sub">Pending orders can be cancelled until we confirm them.</p>
  @forelse ($orders as $o)
    <article class="order">
      <div class="oh"><h3>Order #{{ $o->id }}</h3><span class="st {{ $o->status }}">{{ ucfirst($o->status) }}</span><small>{{ $o->created_at->format('M d, Y g:i A') }}</small></div>
      <ul>@foreach ($o->items as $i)<li><span>{{ $i->qty }} × {{ $i->name }}</span><span>₱{{ number_format($i->price * $i->qty, 2) }}</span></li>@endforeach</ul>
      <div class="of"><div><small>{{ $o->payment }} · {{ $o->address }}</small></div>
        <b>₱{{ number_format($o->total, 2) }}</b>
        @if ($o->status === 'pending')
          <form method="POST" action="{{ route('orders.cancel', $o) }}" onsubmit="return confirm('Cancel order #{{ $o->id }}?')">@csrf @method('PATCH')<button class="pill small danger">Cancel order</button></form>
        @endif
      </div>
    </article>
  @empty
    <p class="empty">You have not placed an order yet. <a href="{{ route('collection') }}">Browse the collection</a>.</p>
  @endforelse
</section>
@endsection
