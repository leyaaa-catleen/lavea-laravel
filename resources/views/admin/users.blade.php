@extends('layouts.app')
@section('title', 'Users — Lavéa Admin')
@section('content')
<section class="section">
  <h1 class="page-h">Users</h1>
  <p class="sub">Make a trusted person an admin so they can manage inventory and orders.</p>
  <div class="table"><table>
    <thead><tr><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Joined</th><th></th></tr></thead><tbody>
    @foreach ($users as $u)
      <tr><td><b>{{ $u->name }}</b></td><td>{{ $u->username }}</td><td>{{ $u->email }}</td>
        <td><span class="st {{ $u->role === 'admin' ? 'confirmed' : '' }}">{{ ucfirst($u->role) }}</span></td>
        <td>{{ $u->created_at->format('M d, Y') }}</td>
        <td>@if ($u->id !== auth()->id())
          <form method="POST" action="{{ route('admin.users.role', $u) }}" onsubmit="return confirm('Change role for {{ addslashes($u->name) }}?')">@csrf @method('PATCH')
            <input type="hidden" name="role" value="{{ $u->role === 'admin' ? 'customer' : 'admin' }}">
            <button class="pill small">{{ $u->role === 'admin' ? 'Make customer' : 'Make admin' }}</button></form>
        @else<small>You</small>@endif</td></tr>
    @endforeach
  </tbody></table></div>
</section>
@endsection
