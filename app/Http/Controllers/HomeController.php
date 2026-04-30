<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Import model Product Anda
// use App\Models\Product; 

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 1. Logika untuk mengambil data produk
        // Jika Anda sudah memiliki model Product, gunakan ini:
        // $query = Product::query();
        
        // Data Dummy untuk testing jika database belum siap:
        $allProducts = collect([
            (object)[
                'nama_produk' => 'Arabika Gayo Premium',
                'brand_umkm' => 'Rumah Kopi Aceh',
                'harga' => 85000,
                'label' => 'Terlaris',
                'rating' => 4.8,
                'gambar' => null,
                'deskripsi' => 'Kopi Arabika Gayo diproses dengan metode full-wash, menghasilkan cita rasa nutty dan spicy yang kuat.'
            ],
            (object)[
                'nama_produk' => 'Robusta Lampung',
                'brand_umkm' => 'Kopi Sidera',
                'harga' => 65000,
                'label' => 'Promo',
                'rating' => 4.9,
                'gambar' => null,
                'deskripsi' => 'Robusta pilihan dengan body tebal dan aroma cokelat yang dominan. Cocok untuk pecinta kopi susu.'
            ],
            (object)[
                'nama_produk' => 'Kintamani Natural',
                'brand_umkm' => 'Bali Coffee Co.',
                'harga' => 95000,
                'label' => 'Baru',
                'rating' => 4.7,
                'gambar' => null,
                'deskripsi' => 'Diproses secara natural untuk menonjolkan rasa fruity jeruk yang segar khas dataran tinggi Kintamani.'
            ],
            (object)[
                'nama_produk' => 'Flores Bajawa',
                'brand_umkm' => 'Kopi Ende NTT',
                'harga' => 78000,
                'label' => null,
                'rating' => 4.6,
                'gambar' => null,
                'deskripsi' => 'Kopi dengan tingkat keasaman rendah dan sentuhan rasa caramel serta tembakau yang unik.'
            ]
        ]);

        // 2. Logika Pencarian (Search)
        $search = $request->input('search');
        if ($search) {
            $products = $allProducts->filter(function($item) use ($search) {
                return str_contains(strtolower($item->nama_produk), strtolower($search)) || 
                       str_contains(strtolower($item->brand_umkm), strtolower($search));
            });
        } else {
            $products = $allProducts;
        }

        // 3. Logika Filter Label
        $filter = $request->input('filter');
        if ($filter) {
            $products = $products->where('label', $filter);
        }

        // 4. Mengirim variabel $products ke view home.blade.php
        return view('home', compact('products'));
    }
}