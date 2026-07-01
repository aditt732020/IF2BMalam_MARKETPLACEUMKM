<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BuyerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
// --- IMPORT CONTROLLER BARU DI SINI ---
use App\Http\Controllers\ReviewController;

// ==================== PUBLIC / GUEST ROUTES ====================
// Halaman Utama/Landing Page sekarang bisa diakses siapa saja (Guest & Auth)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ==================== AUTH ROUTES (Harus Login) ====================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout'); // Sudah diubah ke GET
    
    // Fitur Keranjang Nyata
    Route::post('/cart/add', [HomeController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [HomeController::class, 'removeFromCart'])->name('cart.remove');
    Route::patch('/cart/update/{id}', [HomeController::class, 'updateCartQuantity'])->name('cart.update');
    
    // Fitur Proses Checkout Terintegrasi
    Route::post('/checkout', [HomeController::class, 'processCheckout'])->name('checkout');
    Route::post('/profile', [HomeController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/orders/{order}/cancel', [HomeController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/payment/validate', [HomeController::class, 'validatePayment'])->name('payment.validate');

    // --- TAMBAHKAN ROUTE ULASAN DI SINI (Di dalam middleware auth) ---
    Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    Route::get('/sellers', [SellerController::class, 'index'])->name('sellers');
    Route::post('/sellers', [SellerController::class, 'store'])->name('sellers.store');
    Route::put('/sellers/{seller}', [SellerController::class, 'update'])->name('sellers.update');
    Route::delete('/sellers/{seller}', [SellerController::class, 'destroy'])->name('sellers.destroy');

    Route::get('/buyers', [BuyerController::class, 'index'])->name('buyers');
    Route::put('/buyers/{buyer}', [BuyerController::class, 'update'])->name('buyers.update');
    Route::delete('/buyers/{buyer}', [BuyerController::class, 'destroy'])->name('buyers.destroy');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');