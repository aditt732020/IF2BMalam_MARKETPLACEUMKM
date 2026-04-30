<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $editOrder = null;
        if ($request->filled('edit')) {
            $editOrder = Order::find($request->integer('edit'));
        }

        $orders = Order::with(['buyer', 'product'])->latest()->paginate(10);
        $buyers = User::where('role', 'buyer')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.orders', compact('orders', 'buyers', 'products', 'editOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buyer_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $validated['total_price'] = $product->price * $validated['quantity'];
        Order::create($validated);

        return redirect()->route('admin.orders')->with('success', 'Pesanan berhasil ditambahkan.');
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'buyer_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $validated['total_price'] = $product->price * $validated['quantity'];
        $order->update($validated);

        return redirect()->route('admin.orders')->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders')->with('success', 'Pesanan berhasil dihapus.');
    }
}
