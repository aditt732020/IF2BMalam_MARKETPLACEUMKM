<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private const DEFAULT_IMAGE = 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=600&q=80';

    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $query = Product::query()
            ->withCount('orders')
            ->where('is_active', true)
            ->where('stock', '>', 0);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('shop_name', 'like', '%' . $search . '%');
            });
        }

        $products = $query->latest()->get();
        $activeCategory = $request->input('category', '');

        $productsJson = $products->map(fn (Product $p) => $this->formatProduct($p))->values();

        $umkmShops = $this->buildUmkmList();
        $categories = Product::categories();

        return view('home', [
            'user' => auth()->user(),
            'products' => $products,
            'productsJson' => $productsJson,
            'umkmShops' => $umkmShops,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'categoryStyles' => Product::categoryStyles(),
        ]);
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::where('is_active', true)->findOrFail($validated['product_id']);

        if ($product->stock < $validated['quantity']) {
            return back()->withErrors([
                'checkout' => 'Stok produk tidak mencukupi. Tersedia: ' . $product->stock . ' unit.',
            ]);
        }

        DB::transaction(function () use ($product, $validated) {
            Order::create([
                'buyer_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'total_price' => $product->price * $validated['quantity'],
                'status' => 'pending',
            ]);

            $product->decrement('stock', $validated['quantity']);
        });

        return redirect()->route('home')->with('success', 'Pesanan berhasil dibuat! Admin akan memproses pembayaran dan pengiriman Anda.');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        auth()->user()->update($validated);

        return redirect()->route('home')->with('success', 'Profil berhasil diperbarui.');
    }

    private function formatProduct(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (int) $product->price,
            'stock' => (int) $product->stock,
            'description' => $product->description ?? '',
            'image_url' => $product->resolveImageUrl(),
            'fallback_image' => self::DEFAULT_IMAGE,
            'shop_name' => $product->shop_name ?? 'UMKM Lokal',
            'category' => $product->category,
            'category_label' => $product->category_label,
            'category_badge' => $product->category_style['badge'],
            'orders_count' => (int) $product->orders_count,
        ];
    }

    private function buildUmkmList()
    {
        $colors = ['#cc8444', '#1fa471', '#2c84e4', '#846cf4', '#c57d38', '#be8146'];

        return Product::query()
            ->where('is_active', true)
            ->whereNotNull('shop_name')
            ->where('shop_name', '!=', '')
            ->get()
            ->groupBy('shop_name')
            ->map(function ($items, $shopName) use ($colors) {
                static $index = 0;
                $seller = User::where('role', 'seller')->where('name', $shopName)->first();

                return [
                    'name' => $shopName,
                    'region' => $seller?->region ?? 'Indonesia',
                    'products_count' => $items->count(),
                    'initials' => strtoupper(mb_substr($shopName, 0, 2)),
                    'color' => $colors[$index++ % count($colors)],
                ];
            })
            ->values();
    }
}
