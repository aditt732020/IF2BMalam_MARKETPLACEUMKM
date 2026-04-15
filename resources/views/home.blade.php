<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KopiNusantara - Marketplace UMKM Kopi Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #3a2010 0%, #5a4030 100%);
        }
        .product-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-[#f9f6f2]">
    
    <!-- Navbar -->
    <nav class="bg-white/90 backdrop-blur-sm sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#c97e3a] rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">K</span>
                    </div>
                    <div>
                        <h1 class="text-[#3a2010] font-bold text-lg">KopiNusantara</h1>
                        <p class="text-[#9a8070] text-xs">Marketplace UMKM Kopi</p>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input type="text" placeholder="Cari kopi, daerah asal, atau nama toko..." 
                               class="w-full px-4 py-2 pl-10 border border-[#e0d8cc] rounded-lg focus:outline-none focus:border-[#c97e3a]">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#9a8070]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-[#9a8070] hover:text-[#c97e3a] text-sm transition">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero-gradient text-white py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                <!-- Left Content -->
                <div class="flex-1 text-center lg:text-left">
                    <div class="inline-block px-3 py-1 bg-[#c97e3a]/20 rounded-full text-[#c97e3a] text-sm mb-4">
                        100% Kopi Lokal Indonesia
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4">
                        Temukan kopi terbaik<br>dari UMKM pilihan
                    </h2>
                    <p class="text-[#d4b896] text-lg mb-8 max-w-lg mx-auto lg:mx-0">
                        Dari petani ke cangkir — dukung pelaku usaha kopi nusantara.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <button class="bg-[#c97e3a] hover:bg-[#a06020] text-white font-semibold px-8 py-3 rounded-lg transition">
                            Belanja Sekarang
                        </button>
                        <button class="border border-white hover:bg-white/10 text-white font-semibold px-8 py-3 rounded-lg transition">
                            Kenali UMKM
                        </button>
                    </div>
                </div>
                
                <!-- Right Image -->
                <div class="flex-1">
                    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=500&h=400&fit=crop" 
                         alt="Kopi" 
                         class="rounded-2xl shadow-2xl w-full max-w-md mx-auto">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Products Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-bold text-[#3a2010]">Produk terlaris</h3>
                <a href="#" class="text-[#c97e3a] hover:underline text-sm font-medium">Lihat semua →</a>
            </div>
            
            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Product 1 -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm product-card cursor-pointer">
                    <div class="relative h-48 bg-gradient-to-br from-[#8B6914] to-[#5a4030] flex items-center justify-center">
                        <span class="text-6xl">☕</span>
                        <span class="absolute top-2 left-2 bg-[#c97e3a] text-white text-xs px-2 py-1 rounded">Terlaris</span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-[#3a2010]">Arabika Gayo Premium</h4>
                        <p class="text-[#9a8070] text-sm">Rumah Kopi Aceh</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[#c97e3a] font-bold">Rp85.000</span>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                                <span class="text-sm text-[#9a8070]">4.8</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product 2 (Promo) -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm product-card cursor-pointer">
                    <div class="relative h-48 bg-gradient-to-br from-[#2d5016] to-[#1a3a0a] flex items-center justify-center">
                        <span class="text-6xl">☕</span>
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">Promo</span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-[#3a2010]">Robusta Lampung</h4>
                        <p class="text-[#9a8070] text-sm">Kopi Sidera</p>
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                <span class="text-[#c97e3a] font-bold">Rp65.000</span>
                                <span class="text-[#9a8070] text-xs line-through ml-2">Rp85.000</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                                <span class="text-sm text-[#9a8070]">4.9</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product 3 (Baru) -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm product-card cursor-pointer">
                    <div class="relative h-48 bg-gradient-to-br from-[#1a4a5a] to-[#0a2a3a] flex items-center justify-center">
                        <span class="text-6xl">☕</span>
                        <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">Baru</span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-[#3a2010]">Kintamani Natural</h4>
                        <p class="text-[#9a8070] text-sm">Bali Coffee Co.</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[#c97e3a] font-bold">Rp95.000</span>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                                <span class="text-sm text-[#9a8070]">4.7</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product 4 -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm product-card cursor-pointer">
                    <div class="relative h-48 bg-gradient-to-br from-[#8B4513] to-[#5a2a0a] flex items-center justify-center">
                        <span class="text-6xl">☕</span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-[#3a2010]">Flores Bajawa</h4>
                        <p class="text-[#9a8070] text-sm">Kopi Ende NTT</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[#c97e3a] font-bold">Rp78.000</span>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                                <span class="text-sm text-[#9a8070]">4.6</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-[#3a2010] text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-center md:text-left">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-[#c97e3a] rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">K</span>
                        </div>
                        <div>
                            <h1 class="text-white font-bold text-lg">KopiNusantara</h1>
                            <p class="text-[#d4b896] text-xs">Marketplace UMKM Kopi</p>
                        </div>
                    </div>
                    <p class="text-[#d4b896] text-sm">© 2025 KopiNusantara - Mendukung UMKM Indonesia</p>
                </div>
                <div class="flex gap-8">
                    <a href="#" class="text-[#d4b896] hover:text-[#c97e3a] text-sm transition">Tentang Kami</a>
                    <a href="#" class="text-[#d4b896] hover:text-[#c97e3a] text-sm transition">Kebijakan Privasi</a>
                    <a href="#" class="text-[#d4b896] hover:text-[#c97e3a] text-sm transition">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </footer>
    
</body>
</html>