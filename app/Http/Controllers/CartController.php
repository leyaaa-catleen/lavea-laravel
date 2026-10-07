<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Perfume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function lines()
    {
        $cart = session('cart', []);
        $ps = Perfume::whereIn('id', array_keys($cart))->get()->keyBy('id');
        return collect($cart)->map(fn ($q, $id) => $ps->has($id) ? ['p' => $ps[$id], 'qty' => min($q, $ps[$id]->stock)] : null)
            ->filter(fn ($l) => $l && $l['qty'] > 0);
    }

    public function show()
    {
        $lines = $this->lines();
        return view('cart', ['lines' => $lines, 'total' => $lines->sum(fn ($l) => $l['p']->price * $l['qty'])]);
    }

    public function add(Request $request, Perfume $perfume)
    {
        $q = $request->validate(['qty' => ['required', 'integer', 'min:1', 'max:20']])['qty'];
        $cart = session('cart', []);
        $new = min(($cart[$perfume->id] ?? 0) + $q, $perfume->stock);
        if ($new < 1) return back()->with('error', "{$perfume->name} is sold out.");
        $cart[$perfume->id] = $new;
        session(['cart' => $cart]);
        return back()->with('success', "{$perfume->name} added to your cart.");
    }

    public function update(Request $request, Perfume $perfume)
    {
        $q = $request->validate(['qty' => ['required', 'integer', 'min:1', 'max:20']])['qty'];
        $cart = session('cart', []);
        $cart[$perfume->id] = min($q, $perfume->stock);
        session(['cart' => $cart]);
        return back();
    }

    public function remove(Perfume $perfume)
    {
        $cart = session('cart', []);
        unset($cart[$perfume->id]);
        session(['cart' => $cart]);
        return back()->with('success', 'Removed from your cart.');
    }

    public function checkout(Request $request)
    {
        $d = $request->validate([
            'phone'   => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            'address' => ['required', 'min:10', 'max:200'],
            'payment' => ['required', 'in:Cash on delivery,GCash'],
            'notes'   => ['nullable', 'max:200'],
        ], [
            'phone.regex' => 'Enter a valid mobile number, like 09171234567.',
            'address.min' => 'Enter your full address: house no., street, barangay, city.',
        ]);

        $cart = session('cart', []);
        if (!$cart) return redirect()->route('cart')->with('error', 'Your cart is empty.');

        try {
            $order = DB::transaction(function () use ($cart, $d) {
                $ps = Perfume::whereIn('id', array_keys($cart))->lockForUpdate()->get()->keyBy('id');
                $total = 0;
                foreach ($cart as $id => $q) {
                    $p = $ps[$id] ?? null;
                    if (!$p || $p->stock < $q) throw new \RuntimeException(($p->name ?? 'An item') . ' no longer has enough stock. Please update your cart.');
                    $total += $p->price * $q;
                }
                $o = Order::create($d + ['user_id' => auth()->id(), 'total' => $total]);
                foreach ($cart as $id => $q) {
                    $p = $ps[$id];
                    $p->decrement('stock', $q);
                    $o->items()->create(['perfume_id' => $id, 'name' => $p->name, 'price' => $p->price, 'qty' => $q]);
                }
                return $o;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('cart')->with('error', $e->getMessage());
        }

        session()->forget('cart');
        return redirect()->route('orders')->with('success', "Order #{$order->id} placed. We will confirm it shortly.");
    }

    public function orders()
    {
        return view('orders', ['orders' => Order::with('items')->where('user_id', auth()->id())->latest()->get()]);
    }

    public function cancel(Order $order)
    {
        abort_unless($order->user_id === auth()->id() && $order->status === 'pending', 403);
        DB::transaction(fn () => $order->cancel());
        return back()->with('success', "Order #{$order->id} cancelled.");
    }
}
