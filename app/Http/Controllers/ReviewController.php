<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReview;

class ReviewController extends Controller
{
    /**
     * Menyimpan ulasan dan rating baru dari pembeli.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form ulasan
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|max:500',
        ]);

        // 2. Cek apakah user sudah membeli dan membayar produk ini
        $hasPurchased = \App\Models\Order::where('buyer_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->whereIn('status', ['paid', 'shipped', 'completed'])
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Anda hanya bisa memberi ulasan untuk produk yang sudah Anda beli dan bayar.');
        }

        // 3. Cek apakah user sudah pernah memberi ulasan untuk produk ini
        $alreadyReviewed = \App\Models\ProductReview::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Anda sudah pernah memberi ulasan untuk produk ini.');
        }

        // 4. Simpan data ulasan ke dalam database
        ProductReview::create([
            'product_id' => $request->product_id,
            'user_id'    => auth()->id(), // Mengambil ID User yang sedang login
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        // 5. Kembali ke halaman produk dengan pesan sukses
        return back()->with('success', 'Ulasan Anda berhasil ditambahkan!');
    }
}