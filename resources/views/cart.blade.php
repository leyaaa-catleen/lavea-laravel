@extends('layouts.app')
@section('title', 'Your cart — Lavéa')
@section('content')
<section class="section">
  <h1 class="page-h">Your cart</h1>
  @if ($errors->any())<div class="alert"><b>Please fix the following:</b><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @if ($lines->isEmpty())
    <p class="empty">Your cart is empty. <a href="{{ route('collection') }}">Browse the collection</a> to add a perfume.</p>
  @else
  <div class="checkout">
    <div class="table"><table>
      <thead><tr><th></th><th>Perfume</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
      <tbody>
      @foreach ($lines as $l)
        <tr>
          <td class="mini">@include('partials.bottle', ['color' => $l['p']->color])</td>
          <td><b>{{ $l['p']->name }}</b><br><small>{{ $l['p']->stock }} available</small></td>
          <td>{{ $l['p']->peso }}</td>
          <td><form method="POST" action="{{ route('cart.update', $l['p']) }}">@csrf @method('PATCH')
            <input class="qin" type="number" name="qty" value="{{ $l['qty'] }}" min="1" max="{{ min(20, $l['p']->stock) }}" onchange="this.form.submit()" aria-label="Quantity"></form></td>
          <td>₱{{ number_format($l['p']->price * $l['qty'], 2) }}</td>
          <td><form method="POST" action="{{ route('cart.remove', $l['p']) }}">@csrf @method('DELETE')<button class="pill small danger">Remove</button></form></td>
        </tr>
      @endforeach
      </tbody></table></div>

    <form method="POST" action="{{ route('checkout') }}" class="co" novalidate data-validate>
      @csrf
      <h2>Delivery</h2>
      <div class="field"><label>Mobile number</label><input name="phone" value="{{ old('phone') }}" placeholder="09171234567" inputmode="tel" required></div>
      <div class="field"><label>Delivery address</label><input name="address" value="{{ old('address') }}" placeholder="House no., street, barangay, city" required minlength="10"></div>
      <div class="field"><label>Payment</label><select name="payment" required><option>Cash on delivery</option><option>GCash</option></select></div>
      <div class="field"><label>Note for the seller (optional)</label><input name="notes" value="{{ old('notes') }}" maxlength="200"></div>
      <p class="total">Total <b>₱{{ number_format($total, 2) }}</b></p>
      <button class="btn full">Place order</button>
    </form>
  </div>
  @endif
</section>
@endsection
