@extends('layouts.app')
@section('title', 'Orders — Lavéa Admin')
@section('content')
<section class="section">
  <h1 class="page-h">Orders</h1>
  <p class="sub">Cancelling an order puts its bottles back in stock.</p>
  <div class="table"><table>
    <thead><tr><th>Order</th><th>Customer</th><th>Items</th><th>Delivery</th><th>Total</th><th>Status</th></tr></thead><tbody>
    @forelse ($orders as $o)
      <tr>
        <td><b>#{{ $o->id }}</b><br><small>{{ $o->created_at->format('M d, g:i A') }}</small></td>
        <td>{{ $o->user->name }}<br><small>{{ $o->phone }}</small></td>
        <td>@foreach ($o->items as $i){{ $i->qty }} × {{ $i->name }}<br>@endforeach</td>
        <td><small>{{ $o->address }}<br>{{ $o->payment }}@if ($o->notes) · “{{ $o->notes }}”@endif</small></td>
        <td>₱{{ number_format($o->total, 2) }}</td>
        <td>
          @if ($o->status === 'cancelled')<span class="st cancelled">Cancelled</span>
          @else
          <form method="POST" action="{{ route('admin.orders.status', $o) }}">@csrf @method('PATCH')
            <select name="status" class="sel" onchange="this.form.submit()" aria-label="Order status">
              @foreach (\App\Models\Order::STATUSES as $s)<option value="{{ $s }}" @selected($o->status === $s)>{{ ucfirst($s) }}</option>@endforeach
            </select></form>
          @endif
        </td>
      </tr>
    @empty<tr><td colspan="6" class="empty">No orders yet.</td></tr>@endforelse
  </tbody></table></div>
</section>
@endsection
