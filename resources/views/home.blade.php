<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KopiNusantara - Marketplace UMKM Kopi Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
@php
    $firstProduct = $products->first();
    $initProduct = $firstProduct ? [
        'id' => $firstProduct->id,
        'name' => $firstProduct->name,
        'price' => (int) $firstProduct->price,
        'stock' => (int) $firstProduct->stock,
        'description' => $firstProduct->description ?? '',
        'image_url' => $firstProduct->resolveImageUrl(),
        'shop_name' => $firstProduct->shop_name ?? 'UMKM Lokal',
        'category' => $firstProduct->category,
        'category_label' => $firstProduct->category_label,
        'category_badge' => $firstProduct->category_style['badge'],
    ] : null;
@endphp
<body class="bg-[#fdf9f4]" x-data="{
    page: 'home',
    isPaymentOpen: false,
    isLoading: false,
    products: @js($productsJson),
    categoryList: @js($categories),
    categoryStyles: @js($categoryStyles),
    selectedCategory: '{{ $activeCategory }}',
    searchQuery: '{{ request('search', '') }}',
    selectedId: {{ $initProduct ? $initProduct['id'] : 'null' }},
    selectedProduct: @js($initProduct ? $initProduct['name'] : ''),
    selectedPrice: {{ $initProduct['price'] ?? 0 }},
    selectedDescription: @js($initProduct ? $initProduct['description'] : ''),
    selectedImg: @js($initProduct ? $initProduct['image_url'] : ''),
    selectedShop: @js($initProduct ? $initProduct['shop_name'] : ''),
    selectedCategoryKey: @js($initProduct ? $initProduct['category'] : ''),
    selectedCategoryLabel: @js($initProduct ? $initProduct['category_label'] : ''),
    selectedCategoryBadge: @js($initProduct ? $initProduct['category_badge'] : ''),
    selectedStock: {{ $initProduct['stock'] ?? 0 }},
    qty: 1,
    detailTab: 'deskripsi',
    paymentMethod: 'QRIS',
    selectedBank: 'BCA',
    voucherCode: '',
    cartChecked: true,
    isProfileDropdownOpen: false,
    userProfile: {
        nama: @js($user?->name ?? ''),
        email: @js($user?->email ?? ''),
        telepon: @js($user?->phone ?? ''),
        alamat: @js($user?->address ?? '')
    },
    selectProduct(p) {
        this.selectedId = p.id;
        this.selectedProduct = p.name;
        this.selectedPrice = p.price;
        this.selectedDescription = p.description;
        this.selectedImg = p.image_url;
        this.selectedShop = p.shop_name;
        this.selectedCategoryKey = p.category;
        this.selectedCategoryLabel = p.category_label;
        this.selectedCategoryBadge = p.category_badge;
        this.selectedStock = p.stock;
        this.qty = 1;
        this.page = 'detail';
    },
    imageFallback(event, url) { event.target.src = url; },
    getCategoryLabel(key) { return this.categoryList[key] || key; },
    get productGroups() {
        return Object.entries(this.categoryList).map(([key, label]) => ({
            key,
            label,
            badge: this.categoryStyles[key]?.badge || 'bg-gray-100 text-gray-600 border border-gray-200',
            items: this.filteredProducts.filter(p => p.category === key),
        })).filter(g => g.items.length > 0);
    },
    get filteredProducts() {
        const q = this.searchQuery.toLowerCase().trim();
        return this.products.filter(p => {
            const matchCat = !this.selectedCategory || p.category === this.selectedCategory;
            const matchSearch = !q || p.name.toLowerCase().includes(q) || (p.shop_name && p.shop_name.toLowerCase().includes(q)) || (p.description && p.description.toLowerCase().includes(q));
            return matchCat && matchSearch;
        });
    },
    get cartCount() { return this.selectedId ? 1 : 0; },
    shopInitials(name) { return (name || 'UM').substring(0, 2).toUpperCase(); },
    submitCheckout() {
        if (!this.selectedId || !@js(auth()->check())) return;
        document.getElementById('checkout-product-id').value = this.selectedId;
        document.getElementById('checkout-quantity').value = this.qty;
        document.getElementById('checkout-form').submit();
    }
}">

  <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        
        {{-- SISI KIRI: Logo Baru Kopi Nusantara Transparan --}}
        <div class="flex items-center cursor-pointer" @click="page = 'home'">
            <img src="{{ asset('image/logo3.png') }}" alt="Logo Kopi Nusantara" class="h-20 w-auto object-contain">
        </div>

        {{-- SISI TENGAH: Menu Navigasi --}}
        <div class="hidden md:flex items-center gap-8 text-sm font-medium">
            <a href="#" @click.prevent="page = 'home'" :class="page === 'home' ? 'text-[#c57d38]' : 'text-gray-500 hover:text-gray-800'">Beranda</a>
            <a href="#" @click.prevent="page = 'home'" class="text-gray-500 hover:text-gray-800">Toko UMKM</a>
            <a href="#" @click.prevent="page = 'home'" class="text-gray-500 hover:text-gray-800">Tentang Kami</a>
        </div>

        {{-- SISI KANAN: Keranjang & Profil/Login --}}
        <div class="flex items-center gap-6 relative">
            <div class="text-gray-700 hover:text-[#c57d38] cursor-pointer transition relative" 
                 @click="if (@js(auth()->check())) { page = 'cart' } else { window.location.href = '{{ route('login') }}' }">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span x-show="cartCount > 0" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold" x-text="cartCount"></span>
            </div>

            @auth
                <div class="relative" @click.away="isProfileDropdownOpen = false">
                    <button @click="isProfileDropdownOpen = !isProfileDropdownOpen" class="text-gray-700 hover:text-[#c57d38] focus:outline-none transition flex items-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>

                    <div x-show="isProfileDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-xl shadow-lg py-2 z-50">
                        
                        <div class="px-4 py-2 border-b border-gray-50">
                            <p class="text-xs text-gray-400">Masuk sebagai</p>
                            <p class="text-xs font-bold text-gray-800 truncate" x-text="userProfile.nama"></p>
                        </div>

                        <a href="#" @click.prevent="page = 'profile'; isProfileDropdownOpen = false" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#fdf3e7] hover:text-[#c57d38] transition font-medium">
                            Edit Profil
                        </a>

                        <a href="{{ route('logout') }}" @click="alert('Anda telah berhasil keluar.'); isProfileDropdownOpen = false" class="block px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition font-medium border-t border-gray-50">
                            Logout
                        </a>
                    </div>
                </div>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="bg-[#c57d38] hover:bg-[#a66528] text-white px-5 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                    Masuk
                </a>
            @endguest
        </div>
    </div>
</nav>
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-6 pt-4">
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        </div>
    @endif
    @if (isset($errors) && $errors->any())
        <div class="max-w-7xl mx-auto px-6 pt-4">
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
            </div>
        </div>
    @endif

    <form id="checkout-form" method="POST" action="{{ route('checkout') }}" class="hidden">
        @csrf
        <input type="hidden" name="product_id" id="checkout-product-id" value="">
        <input type="hidden" name="quantity" id="checkout-quantity" value="1">
    </form>
    
    <main x-show="page === 'home'">
        <section class="max-w-7xl mx-auto px-6 pt-10 pb-6">
            <div class="bg-[#f5ebd9] rounded-2xl p-8 lg:p-12 flex flex-col lg:flex-row items-center justify-between gap-8 relative overflow-hidden">
                <div class="max-w-xl z-10">
                    <span class="inline-block px-3 py-1 bg-[#ede0c8] text-[#936232] text-xs font-semibold rounded-full mb-4">100% Kopi Lokal Indonesia</span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-[#3a1f0b] leading-tight mb-4">Temukan kopi terbaik dari UMKM pilihan</h2>
                    <p class="text-gray-600 text-sm mb-8">Dari petani ke cangkir — dukung pelaku usaha kopi nusantara.</p>
                    <div class="flex flex-wrap gap-3">
                        <button class="bg-[#c57d38] hover:bg-[#a66528] text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition" @click="searchQuery = ''">Belanja Sekarang</button>
                        <button class="border border-[#c57d38] text-[#c57d38] hover:bg-[#c57d38]/5 px-6 py-2.5 rounded-lg text-sm font-semibold transition">Kenali UMKM</button>
                    </div>
                </div>
                <div class="w-full lg:w-[420px] h-[240px] rounded-xl overflow-hidden shadow-md">
                    <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80" alt="Ilustrasi Kopi" class="w-full h-full object-cover">
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 py-4">
            <form method="GET" action="{{ route('home') }}" class="flex gap-3">
                <div class="flex-1">
                    <input type="text" name="search" x-model="searchQuery" value="{{ request('search') }}" placeholder="Cari kopi, daerah asal, atau nama toko..."
                           class="w-full px-4 py-3 bg-[#f3ece2] text-sm text-gray-700 placeholder-gray-400 rounded-lg focus:outline-none border border-transparent focus:border-[#c57d38]/30">
                </div>
                @if (request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <a href="{{ route('home') }}" class="bg-[#f3ece2] hover:bg-[#ebd8bc] text-gray-600 px-6 py-3 rounded-lg text-sm font-medium transition flex items-center">Reset</a>
                <button type="submit" class="bg-[#c57d38] hover:bg-[#a66528] text-white px-8 py-3 rounded-lg text-sm font-semibold transition">Cari</button>
            </form>
        </section>
        
        <section class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800 text-base">Kategori produk</h3>
                <a href="#" class="text-xs text-[#c57d38] font-medium hover:underline">Lihat semua</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                @php
                    $catStyles = [
                        'biji_kopi' => ['bg' => 'bg-[#dfb287]/20', 'text' => 'text-[#be8146]'],
                        'kopi_bubuk' => ['bg' => 'bg-[#70c9a5]/20', 'text' => 'text-[#30976f]'],
                        'cold_brew' => ['bg' => 'bg-[#7cb0ec]/20', 'text' => 'text-[#3d7ecb]'],
                        'lainnya' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                    ];
                @endphp
                <a href="{{ route('home', request()->only('search')) }}"
                   class="bg-white border rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:shadow-sm transition {{ !request('category') ? 'border-[#c57d38] ring-1 ring-[#c57d38]/30' : 'border-gray-100' }}">
                    <div class="w-10 h-10 bg-[#f3ece2] text-[#c57d38] rounded-xl mb-2 flex items-center justify-center font-bold text-xs">All</div>
                    <span class="text-xs text-gray-600 font-medium">Semua</span>
                </a>
                @foreach ($categories as $key => $label)
                    @php $style = $catStyles[$key] ?? $catStyles['lainnya']; @endphp
                    <a href="{{ route('home', ['category' => $key, 'search' => request('search')]) }}"
                       class="bg-white border rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:shadow-sm transition {{ request('category') === $key ? 'border-[#c57d38] ring-1 ring-[#c57d38]/30' : 'border-gray-100' }}">
                        <div class="w-10 h-10 {{ $style['bg'] }} {{ $style['text'] }} rounded-xl mb-2 flex items-center justify-center font-bold text-lg">☕</div>
                        <span class="text-xs text-gray-600 font-medium">{{ $label }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-bold text-gray-800 text-base">Produk UMKM Kopi</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $products->count() }} produk tersedia dari database</p>
                </div>
                <span class="text-xs text-[#c57d38] font-medium" x-show="filteredProducts.length < products.length" x-text="filteredProducts.length + ' ditampilkan'"></span>
            </div>
            
            {{-- Satu kategori dipilih --}}
            <div x-show="selectedCategory" x-cloak>
                <div class="mb-4 flex items-center gap-2">
                    <span class="inline-flex rounded-full border px-3 py-1 text-xs font-bold" :class="categoryStyles[selectedCategory]?.badge || 'bg-gray-100 text-gray-600'" x-text="getCategoryLabel(selectedCategory)"></span>
                    <span class="text-xs text-gray-400" x-text="filteredProducts.length + ' produk'"></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="bg-white rounded-xl overflow-hidden border border-gray-100 flex flex-col cursor-pointer hover:shadow-md transition"
                             @click="selectProduct(product)">
                            <div class="relative h-44 bg-gray-100 overflow-hidden">
                                <img :src="product.image_url" :alt="product.name" loading="lazy"
                                     x-on:error="imageFallback($event, product.fallback_image)"
                                     class="w-full h-full object-cover">
                                <span class="absolute left-3 top-3 inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold shadow-sm" :class="product.category_badge" x-text="product.category_label"></span>
                            </div>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm mb-0.5" x-text="product.name"></h4>
                                    <p class="text-gray-400 text-xs mb-2" x-text="product.shop_name"></p>
                                </div>
                                <div>
                                    <span class="text-[#c57d38] font-bold text-sm block mb-1" x-text="'Rp ' + product.price.toLocaleString('id-ID')"></span>
                                    <p class="text-[11px] text-gray-400"><span x-text="product.orders_count + ' terjual'"></span></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Semua kategori — dikelompokkan --}}
            <div x-show="!selectedCategory" class="space-y-10">
                <template x-for="group in productGroups" :key="group.key">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-bold" :class="group.badge" x-text="group.label"></span>
                            <span class="text-xs text-gray-400" x-text="group.items.length + ' produk'"></span>
                            <a :href="'{{ url('/') }}?category=' + group.key" class="ml-auto text-xs font-semibold text-[#c57d38] hover:underline">Lihat semua</a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <template x-for="product in group.items" :key="product.id">
                                <div class="bg-white rounded-xl overflow-hidden border border-gray-100 flex flex-col cursor-pointer hover:shadow-md transition"
                                     @click="selectProduct(product)">
                                    <div class="relative h-44 bg-gray-100 overflow-hidden">
                                        <img :src="product.image_url" :alt="product.name" loading="lazy"
                                             x-on:error="imageFallback($event, product.fallback_image)"
                                             class="w-full h-full object-cover">
                                        <span class="absolute left-3 top-3 inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold shadow-sm" :class="product.category_badge" x-text="product.category_label"></span>
                                    </div>
                                    <div class="p-4 flex-1 flex flex-col justify-between">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm mb-0.5" x-text="product.name"></h4>
                                            <p class="text-gray-400 text-xs mb-2" x-text="product.shop_name"></p>
                                        </div>
                                        <div>
                                            <span class="text-[#c57d38] font-bold text-sm block mb-1" x-text="'Rp ' + product.price.toLocaleString('id-ID')"></span>
                                            <p class="text-[11px] text-gray-400"><span x-text="product.orders_count + ' terjual'"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <div x-show="filteredProducts.length === 0" class="text-center py-12 text-gray-400 text-sm">
                @if ($products->isEmpty())
                    Belum ada produk di database. Login sebagai admin untuk menambahkan produk, atau jalankan: <code class="text-xs bg-gray-100 px-2 py-1 rounded">php artisan db:seed --class=MarketplaceSeeder</code>
                @else
                    Tidak ada produk yang cocok dengan pencarian atau filter.
                @endif
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 py-6 mb-16">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800 text-base">UMKM unggulan</h3>
                <a href="#" class="text-xs text-[#c57d38] font-medium hover:underline">Lihat semua</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse ($umkmShops as $shop)
                    <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-center gap-4 hover:shadow-sm transition">
                        <div class="w-12 h-12 rounded-full text-white flex items-center justify-center font-bold text-sm" style="background-color: {{ $shop['color'] }}">{{ $shop['initials'] }}</div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">{{ $shop['name'] }}</h4>
                            <p class="text-gray-400 text-xs mb-1">{{ $shop['region'] }}</p>
                            <span class="text-[#c57d38] text-[11px] font-medium">{{ $shop['products_count'] }} produk</span>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-sm text-gray-400 py-6">Belum ada UMKM terdaftar. Tambahkan penjual dan produk melalui panel admin.</p>
                @endforelse
            </div>
        </section>
    </main>

    <main x-show="page === 'detail'" x-cloak class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 cursor-pointer hover:text-gray-800" @click="page = 'home'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="font-semibold">Detail Produk</span>
        </div>

        <div class="flex flex-col lg:flex-row gap-10 bg-transparent mb-10">
            <div class="w-full lg:w-1/2 space-y-4">
                <div class="w-full h-[400px] bg-gray-200 rounded-2xl overflow-hidden shadow-sm">
                    <img :src="selectedImg" alt="Foto Kopi" class="w-full h-full object-cover"
                         x-on:error="imageFallback($event, 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?w=600&h=440&fit=crop&q=80')">
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex flex-col justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-bold" :class="selectedCategoryBadge" x-text="selectedCategoryLabel"></span>
                        <span class="inline-block text-xs font-bold px-2.5 py-1 rounded"
                              :class="selectedStock > 0 ? 'bg-[#eaf7f2] text-[#42b286]' : 'bg-red-50 text-red-500'"
                              x-text="selectedStock > 0 ? 'Stok ada' : 'Stok habis'"></span>
                    </div>
                    <h2 class="text-2xl font-extrabold text-gray-800 mb-1" x-text="selectedProduct"></h2>
                    <p class="text-sm text-gray-400 mb-4">250g • <span x-text="selectedCategoryLabel"></span></p>
                    
                    <div class="flex items-baseline gap-3 mb-4">
                        <span class="text-3xl font-black text-[#c57d38]" x-text="'Rp ' + selectedPrice.toLocaleString('id-ID')"></span>
                    </div>

                    <div class="flex items-center gap-1 text-sm text-gray-500 mb-6">
                        <span class="text-yellow-500 text-base">★</span>
                        <span class="font-bold text-gray-700" x-text="selectedCategoryLabel"></span>
                        <span>• Stok: <span x-text="selectedStock"></span> pcs</span>
                    </div>

                    <div class="border-t border-b border-gray-100 py-4 mb-6">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Deskripsi produk</p>
                        <p class="text-sm text-gray-600 leading-relaxed" x-text="selectedDescription"></p>
                    </div>

                    <div class="flex items-center justify-between border border-gray-100 rounded-xl p-4 bg-white mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#cc8444] text-white flex items-center justify-center font-bold text-xs" x-text="shopInitials(selectedShop)"></div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-800" x-text="selectedShop"></h4>
                                <p class="text-xs text-gray-400">UMKM Kopi Nusantara • Respons cepat</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500 font-medium">Jumlah</span>
                        <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg p-1.5">
                            <button @click="if(qty > 1) qty--" class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded font-bold hover:bg-gray-100">-</button>
                            <span class="w-6 text-center font-bold text-sm" x-text="qty"></span>
                            <button @click="if(qty < selectedStock) qty++" class="w-8 h-8 flex items-center justify-center bg-[#c57d38] text-white rounded font-bold hover:bg-[#a66528]">+</button>
                        </div>
                        <span class="text-xs text-gray-400">Stok: <span x-text="selectedStock"></span> pcs</span>
                    </div>

                    <div class="flex gap-3">
                        <button @click="if (@js(auth()->check())) { if(selectedId) page = 'cart'; } else { window.location.href = '{{ route('login') }}' }"
                                :disabled="!selectedId"
                                class="flex-1 border-2 border-[#c57d38] text-[#c57d38] font-bold py-3.5 rounded-xl hover:bg-[#c57d38]/5 transition text-sm disabled:opacity-50">
                            + Tambah ke Keranjang
                        </button>
                        <button @click="if (@js(auth()->check())) { if(selectedId) isPaymentOpen = true; } else { window.location.href = '{{ route('login') }}' }"
                                :disabled="!selectedId"
                                class="flex-1 bg-[#c57d38] text-white font-bold py-3.5 rounded-xl hover:bg-[#a66528] transition shadow-md text-sm disabled:opacity-50">
                            Beli Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-transparent border-t border-gray-100 pt-6">
            <div class="flex gap-8 border-b border-gray-100 text-sm font-medium mb-6">
                <button @click="detailTab = 'deskripsi'" :class="detailTab === 'deskripsi' ? 'text-[#c57d38] border-b-2 border-[#c57d38] pb-3' : 'text-gray-400 pb-3'">Deskripsi</button>
                <button @click="detailTab = 'ulasan'" :class="detailTab === 'ulasan' ? 'text-[#c57d38] border-b-2 border-[#c57d38] pb-3' : 'text-gray-400 pb-3'">Ulasan (120)</button>
                <button @click="detailTab = 'tanya'" :class="detailTab === 'tanya' ? 'text-[#c57d38] border-b-2 border-[#c57d38] pb-3' : 'text-gray-400 pb-3'">Tanya Jawab</button>
                <button @click="detailTab = 'kirim'" :class="detailTab === 'kirim' ? 'text-[#c57d38] border-b-2 border-[#c57d38] pb-3' : 'text-gray-400 pb-3'">Pengiriman</button>
            </div>

            <div x-show="detailTab === 'deskripsi'" class="text-sm text-gray-600 space-y-2">
                <p x-text="selectedDescription"></p>
            </div>

            <div x-show="detailTab === 'ulasan'" class="flex flex-col md:flex-row gap-8">
                <div class="flex-1 space-y-6">
                    <div class="border-b border-gray-50 pb-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-xs">AS</div>
                            <div>
                                <h5 class="font-bold text-sm text-gray-800">Andi Setiawan</h5>
                                <p class="text-yellow-500 text-xs">★★★★★</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Aromanya luar biasa, cocok buat pour over. Pengiriman cepat dan aman.</p>
                    </div>
                </div>

                <div class="w-full md:w-80 bg-white border border-gray-100 rounded-2xl p-6 h-fit text-center">
                    <h3 class="text-5xl font-black text-gray-800 mb-1">4.9</h3>
                    <p class="text-yellow-500 mb-1">★★★★★</p>
                    <p class="text-xs text-gray-400">dari 120 ulasan</p>
                </div>
            </div>
        </div>
    </main>

    <main x-show="page === 'cart'" x-cloak class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 cursor-pointer hover:text-gray-800" @click="page = 'home'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="font-semibold">Keranjang Belanja</span>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="w-full lg:flex-1 space-y-6">
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-gray-50/70 px-6 py-3 border-b border-gray-100 flex items-center justify-between text-sm">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" x-model="cartChecked" class="w-4 h-4 rounded border-gray-300 text-[#c57d38] focus:ring-[#c57d38]">
                            <span class="font-bold text-gray-700">Pilih semua (1 item)</span>
                        </label>
                        <button class="text-red-500 font-semibold text-xs hover:underline">Hapus dipilih</button>
                    </div>

                    <div class="px-6 py-3 border-b border-gray-50 flex items-center gap-2 bg-white" x-show="selectedShop">
                        <div class="w-5 h-5 rounded-full bg-[#cc8444] text-white flex items-center justify-center text-[10px] font-bold" x-text="shopInitials(selectedShop)"></div>
                        <span class="text-xs font-bold text-gray-800" x-text="selectedShop"></span>
                    </div>

                    <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-white">
                        <input type="checkbox" x-model="cartChecked" class="w-4 h-4 rounded border-gray-300 text-[#c57d38] focus:ring-[#c57d38] mt-4 sm:mt-0">
                        
                        <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                            <img :src="selectedImg" class="w-full h-full object-cover"
                                 x-on:error="imageFallback($event, 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?w=200&h=200&fit=crop&q=80')">
                        </div>

                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm leading-tight" x-text="selectedProduct + ' 250g'"></h4>
                            <p class="text-xs text-gray-400 mt-1" x-text="'Kategori: ' + selectedCategoryLabel"></p>
                            <span class="text-[#c57d38] font-extrabold text-sm block mt-2" x-text="'Rp ' + selectedPrice.toLocaleString('id-ID')"></span>
                        </div>

                        <div class="flex items-center gap-4 self-end sm:self-center">
                            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg p-1">
                                <button @click="if(qty > 1) qty--" class="w-7 h-7 flex items-center justify-center bg-white rounded shadow-sm font-bold text-gray-600 hover:bg-gray-100">-</button>
                                <span class="w-6 text-center font-bold text-xs text-gray-800" x-text="qty"></span>
                                <button @click="if(qty < selectedStock) qty++" class="w-7 h-7 flex items-center justify-center bg-[#c57d38] text-white rounded shadow-sm font-bold hover:bg-[#a66528]">+</button>
                            </div>
                            <button @click="qty = 1; cartChecked = false" class="text-gray-400 hover:text-red-500 text-xs font-semibold transition">Hapus</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-4">
                    <h4 class="font-bold text-gray-800 text-sm">Kupon & Promo</h4>
                    <div class="flex gap-3">
                        <input type="text" x-model="voucherCode" placeholder="Masukkan kode voucher..." 
                               class="flex-1 px-4 py-2.5 bg-gray-50 text-sm text-gray-700 placeholder-gray-400 rounded-xl focus:outline-none border border-gray-200 focus:border-[#c57d38]/30">
                        <button @click="alert('Voucher tidak valid atau kuota habis')" class="bg-[#c57d38] hover:bg-[#a66528] text-white px-6 py-2.5 rounded-xl text-sm font-bold transition">Pakai</button>
                    </div>

                    <div class="bg-[#fdf3e7] border border-[#c57d38]/20 rounded-xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-[#c57d38] text-white text-[10px] font-black px-2 py-1 rounded">PROMO</div>
                            <div>
                                <h5 class="font-bold text-xs text-[#3a2010]">Gratis ongkir hari ini! Min. Rp 150.000</h5>
                                <p class="text-[11px] text-gray-400 mt-0.5">Berlaku s/d 23:59 WIB</p>
                            </div>
                        </div>
                        <a href="#" class="text-xs text-gray-400 hover:text-[#c57d38] font-medium underline">Syarat & ketentuan</a>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-96 space-y-6">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-6">
                    <h3 class="font-bold text-gray-800 text-sm border-b border-gray-50 pb-3">Ringkasan Pesanan</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-500">
                            <span>Subtotal (<span x-text="cartChecked ? qty : 0"></span> item)</span>
                            <span class="font-medium text-gray-800" x-text="cartChecked ? 'Rp ' + (selectedPrice * qty).toLocaleString('id-ID') : 'Rp 0'"></span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Ongkos kirim</span>
                            <span class="text-green-600 font-bold">Gratis</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Biaya layanan</span>
                            <span class="font-medium text-gray-800" x-text="cartChecked ? 'Rp 1.000' : 'Rp 0'"></span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-100 pt-4 flex justify-between items-center">
                        <span class="font-bold text-sm text-gray-800">Total Pembayaran</span>
                        <span class="text-lg font-black text-[#c57d38]" x-text="cartChecked ? 'Rp ' + ((selectedPrice * qty) + 1000).toLocaleString('id-ID') : 'Rp 0'"></span>
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Estimasi pengiriman</span>
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 flex justify-between items-center text-xs">
                            <div>
                                <h5 class="font-bold text-gray-800">JNE Reguler · Rp 0</h5>
                                <p class="text-gray-400 mt-0.5">Estimasi tiba: 2–3 hari kerja</p>
                                <p class="text-[10px] text-gray-400 mt-1 italic">Dikirim dari: Aceh Tengah & Lampung Barat</p>
                            </div>
                            <button class="text-[#c57d38] font-bold hover:underline">Ubah</button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Metode pembayaran</span>
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 flex justify-between items-center text-xs">
                            <div class="flex items-center gap-2">
                                <div class="bg-gray-800 text-white font-bold px-1.5 py-0.5 rounded text-[9px]">BANK</div>
                                <div>
                                    <h5 class="font-bold text-gray-800">Transfer Bank BCA</h5>
                                    <p class="text-gray-400 mt-0.5">...1234</p>
                                </div>
                            </div>
                            <button class="text-gray-400 font-medium hover:text-gray-700">Ganti</button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Alamat pengiriman</span>
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 flex justify-between items-start text-xs">
                            <div>
                                <h5 class="font-bold text-gray-800" x-text="userProfile.nama + ' · ' + userProfile.telepon"></h5>
                                <p class="text-gray-400 mt-1 leading-relaxed" x-text="userProfile.alamat"></p>
                            </div>
                            <button @click="page = 'profile'" class="text-[#c57d38] font-bold hover:underline">Ubah</button>
                        </div>
                    </div>

                    <button type="button" class="w-full bg-[#c57d38] text-white font-bold py-4 rounded-xl shadow-md hover:bg-[#a66528] transition text-sm flex items-center justify-center gap-2"
                            :disabled="!cartChecked || isLoading || !selectedId"
                            :class="(!cartChecked || !selectedId) ? 'opacity-50 cursor-not-allowed' : ''"
                            @click="if (@js(auth()->check())) { isLoading = true; submitCheckout(); } else { window.location.href = '{{ route('login') }}' }">
                        <span x-text="isLoading ? 'Memproses Transaksi...' : 'Lanjutkan ke Pembayaran'"></span>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <main x-show="page === 'profile'" x-cloak class="max-w-4xl mx-auto px-6 py-8">
        @guest
        <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center">
            <p class="text-gray-500 mb-4">Silakan masuk untuk mengedit profil.</p>
            <a href="{{ route('login') }}" class="inline-block bg-[#c57d38] text-white px-6 py-2.5 rounded-xl text-sm font-bold">Masuk</a>
        </div>
        @else
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 cursor-pointer hover:text-gray-800" @click="page = 'home'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="font-semibold">Kembali ke Beranda</span>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-bold text-gray-800 mb-2">Edit Profil Pengguna</h2>
            <p class="text-xs text-gray-400 mb-6">Perbarui informasi profil Anda untuk keperluan pengiriman dan transaksi yang aman.</p>
            
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="name" x-model="userProfile.nama" required
                           class="w-full px-4 py-2.5 bg-gray-50 text-sm text-gray-700 rounded-xl focus:outline-none border border-gray-200 focus:border-[#c57d38]/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Alamat Email</label>
                    <input type="email" name="email" x-model="userProfile.email" required
                           class="w-full px-4 py-2.5 bg-gray-50 text-sm text-gray-700 rounded-xl focus:outline-none border border-gray-200 focus:border-[#c57d38]/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nomor Telepon</label>
                    <input type="text" name="phone" x-model="userProfile.telepon" required
                           class="w-full px-4 py-2.5 bg-gray-50 text-sm text-gray-700 rounded-xl focus:outline-none border border-gray-200 focus:border-[#c57d38]/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Alamat Pengiriman</label>
                    <textarea name="address" x-model="userProfile.alamat" rows="3" required
                              class="w-full px-4 py-2.5 bg-gray-50 text-sm text-gray-700 rounded-xl focus:outline-none border border-gray-200 focus:border-[#c57d38]/40"></textarea>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="page = 'home'" class="flex-1 border border-gray-200 text-gray-600 font-bold py-3 rounded-xl hover:bg-gray-50 transition text-sm">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-[#c57d38] text-white font-bold py-3 rounded-xl hover:bg-[#a66528] transition shadow-md text-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
        @endguest
    </main>

    <div x-show="isPaymentOpen" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-sm" @click="isPaymentOpen = false"></div>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="bg-[#3a2010] px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="text-base font-bold">Detail Pembayaran Instan</h3>
                    <button @click="isPaymentOpen = false" class="text-white/80 hover:text-white">✕</button>
                </div>

                <div class="px-6 py-5 space-y-5 max-h-[80vh] overflow-y-auto">
                    <div class="bg-[#fdf3e7] p-4 rounded-xl border border-[#c57d38]/20 flex justify-between items-center">
                        <div>
                            <span class="text-xs text-gray-400 font-medium block">Kopi Pilihan</span>
                            <span class="text-gray-800 font-bold text-sm" x-text="selectedProduct + ' (x' + qty + ')'"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-400 font-medium block">Total Tagihan</span>
                            <span class="text-[#c57d38] font-black text-base" x-text="'Rp ' + (selectedPrice * qty).toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-gray-500 font-bold uppercase tracking-wider block mb-2">Pilih Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="pay" value="QRIS" x-model="paymentMethod" class="hidden peer">
                                <div class="p-3 border rounded-xl flex flex-col items-center justify-center gap-1 peer-checked:border-[#c57d38] peer-checked:bg-[#c57d38]/5 transition">
                                    <span class="font-extrabold text-sm text-gray-800">QRIS</span>
                                    <span class="text-[10px] text-gray-400">E-Wallet / M-Banking</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="pay" value="Transfer" x-model="paymentMethod" class="hidden peer">
                                <div class="p-3 border rounded-xl flex flex-col items-center justify-center gap-1 peer-checked:border-[#c57d38] peer-checked:bg-[#c57d38]/5 transition">
                                    <span class="font-extrabold text-sm text-gray-800">Transfer Bank</span>
                                    <span class="text-[10px] text-gray-400">Virtual Account</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div x-show="paymentMethod === 'QRIS'" class="border border-gray-100 rounded-2xl p-5 bg-gray-50 text-center space-y-4">
                        <div class="text-xs font-bold text-gray-700 uppercase tracking-wide">Scan QR Code KopiNusantara</div>
                        
                        <div class="w-44 h-44 bg-white p-2 border-2 border-gray-200 mx-auto rounded-xl shadow-inner flex items-center justify-center relative">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=KopiNusantaraUMKM" alt="QRIS Barcode" class="w-full h-full object-contain">
                        </div>
                        
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500">Mendukung: GoPay, OVO, Dana, LinkAja, BCA Mobile, dll.</p>
                            <p class="text-[11px] text-red-500 font-medium">Lakukan screenshot atau scan barcode di atas sebelum menekan tombol konfirmasi.</p>
                        </div>
                    </div>

                    <div x-show="paymentMethod === 'Transfer'" class="border border-gray-100 rounded-2xl p-5 bg-gray-50 space-y-4">
                        <label class="text-xs text-gray-500 font-bold uppercase tracking-wider block">Pilih Bank Transfer:</label>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <button @click="selectedBank = 'BCA'" :class="selectedBank === 'BCA' ? 'border-[#c57d38] bg-[#c57d38]/5 font-bold text-[#c57d38]' : 'border-gray-200 bg-white text-gray-600'" type="button" class="p-2 border rounded-lg text-xs transition">BCA</button>
                            <button @click="selectedBank = 'Mandiri'" :class="selectedBank === 'Mandiri' ? 'border-[#c57d38] bg-[#c57d38]/5 font-bold text-[#c57d38]' : 'border-gray-200 bg-white text-gray-600'" type="button" class="p-2 border rounded-lg text-xs transition">Mandiri</button>
                            <button @click="selectedBank = 'BNI'" :class="selectedBank === 'BNI' ? 'border-[#c57d38] bg-[#c57d38]/5 font-bold text-[#c57d38]' : 'border-gray-200 bg-white text-gray-600'" type="button" class="p-2 border rounded-lg text-xs transition">BNI</button>
                            <button @click="selectedBank = 'BRI'" :class="selectedBank === 'BRI' ? 'border-[#c57d38] bg-[#c57d38]/5 font-bold text-[#c57d38]' : 'border-gray-200 bg-white text-gray-600'" type="button" class="p-2 border rounded-lg text-xs transition">BRI</button>
                        </div>

                        <div class="bg-white border border-gray-100 p-4 rounded-xl mt-3 space-y-3">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-400 font-medium">Bank Tujuan:</span>
                                <span class="font-extrabold text-gray-800" x-text="selectedBank + ' Virtual Account'"></span>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 px-3 py-2.5 rounded-lg border border-gray-100">
                                <span class="font-mono text-sm tracking-wider font-bold text-gray-700" 
                                      x-text="selectedBank === 'BCA' ? '800113312511139' : (selectedBank === 'Mandiri' ? '700123312511139' : (selectedBank === 'BNI' ? '880233312511139' : '900143312511139'))"></span>
                                <button @click="alert('Nomor Virtual Account berhasil disalin!')" type="button" class="text-xs text-[#c57d38] font-bold hover:underline">Salin</button>
                            </div>
                            <ul class="text-[11px] text-gray-400 list-disc list-inside space-y-0.5">
                                <li>Bisa dibayarkan melalui Mobile Banking, Internet Banking, atau ATM.</li>
                                <li>Transaksi diverifikasi otomatis dalam beberapa menit setelah transfer.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-dashed border-gray-200">
                        <button type="button" class="w-full bg-[#c57d38] text-white font-bold py-3.5 rounded-xl shadow-lg hover:bg-[#a66528] transition flex items-center justify-center gap-2"
                                :disabled="isLoading || !selectedId"
                                @click="if (@js(auth()->check())) { isLoading = true; submitCheckout(); } else { window.location.href = '{{ route('login') }}' }">
                            <span x-text="isLoading ? 'Memproses Pesanan...' : 'Saya Sudah Melakukan Pembayaran'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>