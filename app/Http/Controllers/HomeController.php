<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    private const DEFAULT_IMAGE = 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=600&q=80';

    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $query = Product::query()
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews', 'orders'])
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
        $productsJson = $products->map(fn(Product $p) => $this->formatProduct($p))->values();
        $umkmShops = $this->buildUmkmList();
        $categories = Product::categories();
        $productReviews = $this->formatProductReviews();

        // AMBIL DATA KERANJANG REAL DARI DATABASE LAINNYA
        $realCartItems = [];
        if (auth()->check()) {
            $realCartItems = Cart::with('product')
                ->where('user_id', auth()->id())
                ->get()
                ->map(function ($cart) {
                    return [
                        'cart_id' => $cart->id,
                        'product_id' => $cart->product_id,
                        'name' => $cart->product->name,
                        'price' => (int) $cart->product->price,
                        'quantity' => $cart->quantity,
                        'stock' => $cart->product->stock,
                        'shop_name' => $cart->product->shop_name ?? 'UMKM Lokal',
                        'image_url' => $cart->product->resolveImageUrl(),
                        'category_label' => $cart->product->category_label,
                    ];
                })->values()->toArray();
        }

        $buyerOrders = $this->formatBuyerOrders();

        return view('home', [
            'user' => auth()->user(),
            'products' => $products,
            'productsJson' => $productsJson,
            'umkmShops' => $umkmShops,
            'categories' => $categories,    
            'activeCategory' => $activeCategory,
            'categoryStyles' => Product::categoryStyles(),
            'realCartItems' => $realCartItems,
            'productReviews' => $productReviews,
            'buyerOrders' => $buyerOrders,
            'orderStatuses' => Order::statuses(),
        ]);
    }

    // FUNGSI BARU: Tambah ke Database Keranjang
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cart) {
            $cart->increment('quantity', $validated['quantity']);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity']
            ]);
        }

        return redirect()->route('home')->with('success', 'Produk berhasil dimasukkan ke keranjang belanja Anda!');
    }

    // FUNGSI BARU: Hapus dari Database Keranjang
    public function removeFromCart($id)
    {
        Cart::where('user_id', auth()->id())->where('id', $id)->delete();
        return redirect()->route('home')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('home', ['page' => 'profile'])
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('home', ['page' => 'profile'])
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function processCheckout(Request $request)
    {
        if ($request->has('cart_ids') && is_array($request->cart_ids) && count($request->cart_ids) > 0) {
            return $this->processCartCheckout($request);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'payment_bank' => 'nullable|string',
        ]);

        $product = Product::where('is_active', true)->findOrFail($validated['product_id']);

        if ($product->stock < $validated['quantity']) {
            return back()->withErrors([
                'checkout' => 'Stok produk tidak mencukupi. Tersedia: ' . $product->stock . ' unit.',
            ]);
        }

        $order = null;
        DB::transaction(function () use ($product, $validated, &$order) {
            $totalWithFees = ($product->price * $validated['quantity']) + 1000;
            
            // Generate unique payment reference
            $paymentReference = 'PAY-' . strtoupper(uniqid()) . '-' . auth()->id();
            
            // Generate QR code data (unique per payment)
            $qrCodeData = json_encode([
                'ref' => $paymentReference,
                'amount' => $totalWithFees,
                'user_id' => auth()->id(),
                'timestamp' => time()
            ]);

            $order = Order::create([
                'buyer_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'total_price' => $totalWithFees,
                'status' => 'pending',
                'payment_reference' => $paymentReference,
                'qr_code' => $qrCodeData,
                'payment_method' => $validated['payment_method'],
                'payment_bank' => $validated['payment_bank'] ?? null,
            ]);

            $product->decrement('stock', $validated['quantity']);

            Cart::where('user_id', auth()->id())->where('product_id', $product->id)->delete();
        });

        return redirect()->route('home', ['page' => 'orders'])->with(
            'success',
            'Pesanan berhasil dibuat! Silakan lakukan pembayaran lalu upload bukti di halaman Pesanan Saya.'
        );
    }

    private function processCartCheckout(Request $request)
    {
        $validated = $request->validate([
            'cart_ids' => 'required|array|min:1',
            'cart_ids.*' => 'required|integer|exists:carts,id',
            'payment_method' => 'required|string',
            'payment_bank' => 'nullable|string',
        ]);

        $carts = Cart::with('product')
            ->where('user_id', auth()->id())
            ->whereIn('id', $validated['cart_ids'])
            ->get();

        if ($carts->count() !== count(array_unique($validated['cart_ids']))) {
            return back()->withErrors([
                'checkout' => 'Beberapa item keranjang tidak valid atau bukan milik Anda.',
            ]);
        }

        foreach ($carts as $cart) {
            if (!$cart->product || !$cart->product->is_active) {
                return back()->withErrors([
                    'checkout' => 'Produk "' . ($cart->product->name ?? 'tidak diketahui') . '" tidak tersedia.',
                ]);
            }

            if ($cart->product->stock < $cart->quantity) {
                return back()->withErrors([
                    'checkout' => 'Stok "' . $cart->product->name . '" tidak mencukupi. Tersedia: ' . $cart->product->stock . ' unit.',
                ]);
            }
        }

        $itemCount = $carts->count();

        DB::transaction(function () use ($carts, $validated) {
            $isFirst = true;

            foreach ($carts as $cart) {
                $product = $cart->product;
                $itemTotal = $product->price * $cart->quantity;
                $totalWithFees = $itemTotal + ($isFirst ? 1000 : 0);
                $isFirst = false;

                // Generate unique payment reference
                $paymentReference = 'PAY-' . strtoupper(uniqid()) . '-' . auth()->id();
                
                // Generate QR code data (unique per payment)
                $qrCodeData = json_encode([
                    'ref' => $paymentReference,
                    'amount' => $totalWithFees,
                    'user_id' => auth()->id(),
                    'timestamp' => time()
                ]);

                Order::create([
                    'buyer_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $cart->quantity,
                    'total_price' => $totalWithFees,
                    'status' => 'pending',
                    'payment_reference' => $paymentReference,
                    'qr_code' => $qrCodeData,
                    'payment_method' => $validated['payment_method'],
                    'payment_bank' => $validated['payment_bank'] ?? null,
                ]);

                $product->decrement('stock', $cart->quantity);
                $cart->delete();
            }
        });

        return redirect()->route('home', ['page' => 'orders'])->with(
            'success',
            'Pesanan ' . $itemCount . ' produk berhasil dibuat! Silakan lakukan pembayaran lalu upload bukti di Pesanan Saya.'
        );
    }

    public function cancelOrder(Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('home', ['page' => 'orders'])->withErrors([
                'checkout' => 'Pesanan hanya dapat dibatalkan saat masih menunggu pembayaran.',
            ]);
        }

        DB::transaction(function () use ($order) {
            if ($order->product) {
                $order->product->increment('stock', $order->quantity);
            }

            if ($order->payment_proof_path) {
                Storage::disk('public')->delete($order->payment_proof_path);
            }

            $order->update(['status' => 'cancelled']);
        });

        return redirect()->route('home', ['page' => 'orders'])
            ->with('success', 'Pesanan #' . $order->id . ' berhasil dibatalkan.');
    }

    public function uploadPaymentProof(Request $request, Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('home', ['page' => 'orders'])->withErrors([
                'payment' => 'Bukti pembayaran hanya dapat diunggah untuk pesanan yang masih menunggu pembayaran.',
            ]);
        }

        if ($order->isAwaitingPaymentVerification()) {
            return redirect()->route('home', ['page' => 'orders'])->withErrors([
                'payment' => 'Bukti pembayaran sudah diunggah dan sedang menunggu verifikasi admin.',
            ]);
        }

        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'payment_proof.required' => 'Silakan pilih file bukti pembayaran.',
            'payment_proof.image' => 'File bukti harus berupa gambar.',
            'payment_proof.mimes' => 'Format bukti pembayaran harus JPG, PNG, atau WEBP.',
            'payment_proof.max' => 'Ukuran bukti pembayaran maksimal 5 MB.',
        ]);

        if ($order->payment_proof_path) {
            Storage::disk('public')->delete($order->payment_proof_path);
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $order->update([
            'payment_proof_path' => $path,
            'payment_proof_uploaded_at' => now(),
            'payment_rejection_reason' => null,
            'payment_verified_at' => null,
            'payment_verified_by' => null,
        ]);

        return redirect()->route('home', ['page' => 'orders'])->with(
            'success',
            'Bukti pembayaran pesanan #' . $order->id . ' berhasil diunggah. Menunggu verifikasi admin.'
        );
    }

    public function validatePayment(Request $request)
    {
        $validated = $request->validate([
            'payment_reference' => 'required|string',
        ]);

        $order = Order::where('payment_reference', $validated['payment_reference'])
            ->where('buyer_id', auth()->id())
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Referensi pembayaran tidak ditemukan.',
            ], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah diproses.',
            ], 400);
        }

        if (!$order->hasPaymentProof()) {
            return response()->json([
                'success' => false,
                'message' => 'Upload bukti pembayaran terlebih dahulu di halaman Pesanan Saya.',
            ], 422);
        }

        if ($order->payment_rejection_reason) {
            return response()->json([
                'success' => false,
                'message' => 'Bukti pembayaran ditolak. Silakan unggah bukti yang baru.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran terkirim. Menunggu verifikasi admin.',
            'order_id' => $order->id,
            'verification_status' => 'awaiting_verification',
        ]);
    }

    private function formatBuyerOrders(): array
    {
        if (!auth()->check()) {
            return [];
        }

        return Order::with('product')
            ->where('buyer_id', auth()->id())
            ->latest()
            ->get()
            ->map(function (Order $order) {
                $qrData = $order->payment_reference ?? 'KopiNusantaraUMKM';

                return [
                    'id' => $order->id,
                    'product_id' => $order->product_id,
                    'product_name' => $order->product->name ?? 'Produk tidak ditemukan',
                    'product_image' => $order->product?->resolveImageUrl() ?? self::DEFAULT_IMAGE,
                    'shop_name' => $order->product->shop_name ?? 'UMKM Lokal',
                    'quantity' => $order->quantity,
                    'total_price' => (int) $order->total_price,
                    'status' => $order->status,
                    'status_label' => $order->status_label,
                    'payment_reference' => $order->payment_reference,
                    'payment_method' => $order->payment_method,
                    'qr_code' => $order->qr_code,
                    'qr_image_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' . urlencode($qrData),
                    'has_payment_proof' => $order->hasPaymentProof(),
                    'payment_proof_url' => $order->payment_proof_path
                        ? Storage::disk('public')->url($order->payment_proof_path)
                        : null,
                    'payment_proof_uploaded_at' => $order->payment_proof_uploaded_at?->format('d M Y, H:i'),
                    'payment_verification_label' => $order->paymentVerificationLabel(),
                    'is_awaiting_verification' => $order->isAwaitingPaymentVerification(),
                    'payment_rejection_reason' => $order->payment_rejection_reason,
                    'created_at' => $order->created_at->format('d M Y, H:i'),
                    'created_at_human' => $order->created_at->diffForHumans(),
                ];
            })
            ->values()
            ->toArray();
    }

    private function formatProductReviews(): array
    {
        return ProductReview::with('user')
            ->latest()
            ->get()
            ->map(function (ProductReview $review) {
                return [
                    'id' => $review->id,
                    'product_id' => $review->product_id,
                    'user_name' => $review->user->name ?? 'Pengguna',
                    'rating' => (int) $review->rating,
                    'comment' => $review->comment,
                    'created_at_human' => $review->created_at->diffForHumans(),
                ];
            })
            ->values()
            ->toArray();
    }

    private function formatProduct(Product $product): array
    {
        $averageRating = round((float) ($product->reviews_avg_rating ?? 0), 1);

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
            'average_rating' => $averageRating,
            'reviews_count' => (int) ($product->reviews_count ?? 0),
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
