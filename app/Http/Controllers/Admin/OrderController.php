<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $editOrder = null;
        if ($request->filled('edit')) {
            $editOrder = Order::find($request->integer('edit'));
        }

        $query = Order::with(['buyer', 'product'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->paginate(10)->withQueryString();
        $statuses = Order::statuses();
        $buyers = $editOrder ? User::where('role', 'buyer')->orderBy('name')->get() : collect();
        $products = $editOrder ? Product::orderBy('name')->get() : collect();

        return view('admin.orders', compact('orders', 'buyers', 'products', 'editOrder', 'statuses'));
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
        if ($order->payment_proof_path) {
            Storage::disk('public')->delete($order->payment_proof_path);
        }

        $order->delete();

        return redirect()->route('admin.orders')->with('success', 'Pesanan berhasil dihapus.');
    }

    public function verifyPayment(Order $order)
    {
        if ($order->status !== 'pending' || !$order->hasPaymentProof()) {
            return redirect()->route('admin.orders')->withErrors([
                'payment' => 'Pesanan tidak memiliki bukti pembayaran yang dapat diverifikasi.',
            ]);
        }

        $order->update([
            'status' => 'paid',
            'payment_verified_at' => now(),
            'payment_verified_by' => auth()->id(),
            'payment_rejection_reason' => null,
        ]);

        return redirect()->route('admin.orders')->with(
            'success',
            'Pembayaran pesanan #' . $order->id . ' berhasil diverifikasi.'
        );
    }

    public function rejectPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_rejection_reason' => 'required|string|max:500',
        ], [
            'payment_rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        if ($order->status !== 'pending' || !$order->hasPaymentProof()) {
            return redirect()->route('admin.orders')->withErrors([
                'payment' => 'Pesanan tidak memiliki bukti pembayaran yang dapat ditolak.',
            ]);
        }

        $order->update([
            'payment_rejection_reason' => $validated['payment_rejection_reason'],
            'payment_verified_at' => null,
            'payment_verified_by' => null,
        ]);

        return redirect()->route('admin.orders')->with(
            'success',
            'Bukti pembayaran pesanan #' . $order->id . ' ditolak. Pembeli dapat mengunggah ulang.'
        );
    }
}
