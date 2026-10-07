@extends('layouts.app')
@section('title', 'Dashboard — Lavéa Admin')
@section('content')
<section class="section">
  <h1 class="page-h">Dashboard</h1>
  <div class="stats">
    <div><b>₱{{ number_format($revenue, 0) }}</b><span>Sales (not counting cancelled)</span></div>
    <div><b>{{ $pending }}</b><span>Orders to confirm</span></div>
    <div class="{{ $low ? 'warn' : '' }}"><b>{{ $low }}</b><span>Low stock</span></div>
    <div><b>{{ $customers }}</b><span>Customers</span></div>
  </div>
  <div class="split">
    <div><h2>Latest orders</h2>
      <div class="table"><table><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead><tbody>
      @forelse ($recent as $o)<tr><td>#{{ $o->id }}</td><td>{{ $o->user->name }}</td><td>₱{{ number_format($o->total, 2) }}</td><td><span class="st {{ $o->status }}">{{ ucfirst($o->status) }}</span></td></tr>
      @empty<tr><td colspan="4" class="empty">No orders yet.</td></tr>@endforelse
      </tbody></table></div>
      <p style="margin-top:14px"><a class="pill small" href="{{ route('admin.orders') }}">Manage orders</a></p></div>
    <div><h2>Needs restocking</h2>
      <ul class="perks">@forelse ($lowItems as $p)<li><b>{{ $p->name }}</b><span class="low">{{ $p->stock }} left</span></li>@empty<li><span>Everything is well stocked.</span></li>@endforelse</ul>
      <p style="margin-top:14px"><a class="pill small" href="{{ route('stock') }}">Open inventory</a></p></div>
  </div>
</section>
@endsection
