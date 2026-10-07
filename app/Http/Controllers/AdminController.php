<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Perfume;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'revenue'   => Order::where('status', '!=', 'cancelled')->sum('total'),
            'pending'   => Order::where('status', 'pending')->count(),
            'low'       => Perfume::where('stock', '<=', 5)->count(),
            'customers' => User::where('role', 'customer')->count(),
            'recent'    => Order::with('user')->latest()->take(5)->get(),
            'lowItems'  => Perfume::where('stock', '<=', 5)->orderBy('stock')->get(),
        ]);
    }

    public function orders() { return view('admin.orders', ['orders' => Order::with(['user', 'items'])->latest()->get()]); }

    public function setStatus(Request $request, Order $order)
    {
        $status = $request->validate(['status' => ['required', 'in:' . implode(',', Order::STATUSES)]])['status'];
        if ($order->status === 'cancelled') return back()->with('error', 'Cancelled orders cannot be changed.');
        DB::transaction(fn () => $status === 'cancelled' ? $order->cancel() : $order->update(['status' => $status]));
        return back()->with('success', "Order #{$order->id} is now {$status}.");
    }

    public function users() { return view('admin.users', ['users' => User::orderBy('role')->orderBy('name')->get()]); }

    public function setRole(Request $request, User $user)
    {
        $role = $request->validate(['role' => ['required', 'in:admin,customer']])['role'];
        if ($user->id === auth()->id()) return back()->with('error', 'You cannot change your own role.');
        $user->update(['role' => $role]);
        return back()->with('success', "{$user->name} is now a {$role}.");
    }
}
