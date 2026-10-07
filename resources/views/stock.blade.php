@extends('layouts.app')
@section('title', 'Inventory — Lavéa')
@section('content')
<section class="section">
  <h1 class="page-h">Inventory</h1>
  <div class="stats">
    <div class="glass"><b>{{ $perfumes->count() }}</b><span>Perfumes shown</span></div>
    <div class="glass"><b>{{ $total }}</b><span>Bottles in stock</span></div>
    <div class="glass {{ $low ? 'warn' : '' }}"><b>{{ $low }}</b><span>Low stock (5 or fewer)</span></div>
  </div>

  @if ($errors->any())<div class="alert"><b>Please fix the following:</b><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

  <div class="bar">
    <form method="GET"><input type="search" name="q" value="{{ request('q') }}" placeholder="Search name or family"></form>
    <button class="btn" data-add>Add perfume</button>
  </div>

  <div class="table glass"><table>
    <thead><tr><th></th><th>Name</th><th>Family</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
    <tbody>
    @forelse ($perfumes as $p)
      <tr>
        <td class="mini">@include('partials.bottle', ['color' => $p->color])</td>
        <td><b>{{ $p->name }}</b><br><small>{{ $p->notes }}</small></td>
        <td>{{ $p->category }}</td>
        <td>{{ $p->peso }}</td>
        <td>
          <form method="POST" action="{{ route('stock.adjust', $p) }}" class="qty">@csrf @method('PATCH')
            <button name="delta" value="-1" aria-label="Remove one">−</button>
            <span class="{{ $p->stock <= 5 ? 'low' : '' }}">{{ $p->stock }}</span>
            <button name="delta" value="1" aria-label="Add one">+</button>
          </form>
          @if ($p->stock <= 5)<small class="low">Low stock</small>@endif
        </td>
        <td><div class="acts">
          <button class="pill small" data-edit='@json($p)'>Edit</button>
          <form method="POST" action="{{ route('stock.destroy', $p) }}" onsubmit="return confirm('Delete {{ addslashes($p->name) }}? This cannot be undone.')">@csrf @method('DELETE')
            <button class="pill small danger">Delete</button></form></div>
        </td>
      </tr>
    @empty
      <tr><td colspan="6" class="empty">No perfumes found. Add one to get started.</td></tr>
    @endforelse
    </tbody></table></div>
</section>

<div class="modal" id="sm" hidden><div class="glass box">
  <button class="x" data-close aria-label="Close">×</button>
  <h2 id="sm-title">Add perfume</h2>
  <form method="POST" action="{{ route('stock.store') }}" id="sm-form" novalidate data-validate>
    @csrf <span id="sm-method"></span>
    <div class="field"><label>Name</label><input name="name" required maxlength="80" value="{{ old('name') }}"></div>
    <div class="field"><label>Family</label><select name="category" required>@foreach (\App\Models\Perfume::CATEGORIES as $c)<option>{{ $c }}</option>@endforeach</select></div>
    <div class="field"><label>Scent notes</label><input name="notes" maxlength="255" placeholder="e.g. Violet leaf, iris, musk"></div>
    <div class="row"><div class="field"><label>Price (₱)</label><input type="number" name="price" step="0.01" min="0" required></div>
    <div class="field"><label>Stock</label><input type="number" name="stock" min="0" step="1" required value="0"></div>
    <div class="field"><label>Bottle colour</label><input type="color" name="color" value="#9b6bb5"></div></div>
    <button class="btn full">Save perfume</button>
  </form>
</div></div>
@endsection
