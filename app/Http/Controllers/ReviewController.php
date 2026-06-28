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

        // 2. Simpan data ulasan ke dalam database
        ProductReview::create([
            'product_id' => $request->product_id,
            'user_id'    => auth()->id(), // Mengambil ID User yang sedang login
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        // 3. Kembali ke halaman produk dengan pesan sukses
        return back()->with('success', 'Ulasan Anda berhasil ditambahkan!');
    }
}