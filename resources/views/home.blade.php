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
<body class="bg-[#fdf9f4]" x-data="{ 
    page: 'home',
    isPaymentOpen: false, 
    isLoading: false,
    selectedProduct: 'Arabika Gayo Premium', 
    selectedPrice: 85000,
    selectedDescription: 'Arabika Gayo dari ketinggian 1.400 mdpl. Proses natural sun-dry menghasilkan cita rasa fruity dengan body sedang dan after taste dark chocolate yang khas. Cocok untuk metode pour over dan V60.',
    selectedImg: 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=600&q=80',
    qty: 1,
    detailTab: 'deskripsi',
    paymentMethod: 'QRIS',
    selectedBank: 'BCA',
    searchQuery: '',
    voucherCode: '',
    cartChecked: true,
    isProfileDropdownOpen: false,
    
    /* State data user untuk Edit Profil */
    userProfile: {
        nama: '{{ auth()->user()->nama ?? "Budi Santoso" }}',
        email: '{{ auth()->user()->email ?? "budi.santoso@email.com" }}',
        telepon: '{{ auth()->user()->telepon ?? "0812-3456-7890" }}',
        alamat: '{{ auth()->user()->alamat ?? "Jl. Sudirman No. 45, Kel. Menteng, Jakarta Pusat, DKI Jakarta 10310" }}'
    }
}">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3 cursor-pointer" @click="page = 'home'">
                <div class="w-9 h-9 bg-[#c57d38] rounded flex items-center justify-center">
                    <span class="text-white font-bold text-lg">K</span>
                </div>
                <div>
                    <h1 class="text-[#3a2010] font-bold text-base leading-tight">KopiNusantara</h1>
                    <p class="text-gray-400 text-[10px] tracking-wide">Marketplace UMKM Kopi</p>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="#" @click.prevent="page = 'home'" :class="page === 'home' ? 'text-[#c57d38]' : 'text-gray-500 hover:text-gray-800'">Beranda</a>
                <a href="#" @click.prevent="page = 'home'" class="text-gray-500 hover:text-gray-800">Toko UMKM</a>
                <a href="#" @click.prevent="page = 'home'" class="text-gray-500 hover:text-gray-800">Tentang Kami</a>
            </div>

            <div class="flex items-center gap-6 relative">
                <div class="text-gray-700 hover:text-[#c57d38] cursor-pointer transition relative" 
                     @click="if (@js(auth()->check())) { page = 'cart' } else { window.location.href = '{{ route('login') }}' }">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">1</span>
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
            <div class="flex gap-3">
                <div class="flex-1">
                    <input type="text" x-model="searchQuery" placeholder="Cari kopi, daerah asal, atau nama toko..." 
                           class="w-full px-4 py-3 bg-[#f3ece2] text-sm text-gray-700 placeholder-gray-400 rounded-lg focus:outline-none border border-transparent focus:border-[#c57d38]/30">
                </div>
                <button class="bg-[#f3ece2] hover:bg-[#ebd8bc] text-gray-600 px-6 py-3 rounded-lg text-sm font-medium transition">Filter</button>
                <button class="bg-[#c57d38] hover:bg-[#a66528] text-white px-8 py-3 rounded-lg text-sm font-semibold transition">Cari</button>
            </div>
        </section>
        
        <section class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800 text-base">Kategori produk</h3>
                <a href="#" class="text-xs text-[#c57d38] font-medium hover:underline">Lihat semua</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                <div class="bg-white border border-gray-100 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:shadow-sm transition">
                    <div class="w-10 h-10 bg-[#dfb287]/20 text-[#be8146] rounded-xl mb-2 flex items-center justify-center font-bold text-xl"></div>
                    <span class="text-xs text-gray-600 font-medium">Biji kopi</span>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:shadow-sm transition">
                    <div class="w-10 h-10 bg-[#70c9a5]/20 text-[#30976f] rounded-xl mb-2 flex items-center justify-center font-bold text-xl"></div>
                    <span class="text-xs text-gray-600 font-medium">Kopi bubuk</span>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:shadow-sm transition">
                    <div class="w-10 h-10 bg-[#7cb0ec]/20 text-[#3d7ecb] rounded-xl mb-2 flex items-center justify-center font-bold text-xl"></div>
                    <span class="text-xs text-gray-600 font-medium">Cold brew</span>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800 text-base">Produk terlaris</h3>
                <a href="#" class="text-xs text-[#c57d38] font-medium hover:underline">Lihat semua</a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl overflow-hidden border border-gray-100 flex flex-col cursor-pointer hover:shadow-md transition"
                     @click="page = 'detail'; selectedProduct = 'Arabika Gayo Premium'; selectedPrice = 85000; qty = 1; selectedImg = 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=600&q=80'; selectedDescription = 'Arabika Gayo dari ketinggian 1.400 mdpl. Proses natural sun-dry menghasilkan cita rasa fruity dengan body sedang dan after taste dark chocolate yang khas. Cocok untuk metode pour over dan V60.'">
                    <div class="h-44 bg-gray-100 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=400&q=80" alt="Arabika Gayo" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm mb-0.5">Arabika Gayo Premium</h4>
                            <p class="text-gray-400 text-xs mb-2">Rumah Kopi Aceh</p>
                        </div>
                        <div>
                            <span class="text-[#c57d38] font-bold text-sm block mb-1">Rp 85.000</span>
                            <div class="flex items-center text-[11px] text-gray-400 gap-1">
                                <span class="text-yellow-500">★</span> 4.9 <span class="mx-1">•</span> 120 terjual
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl overflow-hidden border border-gray-100 flex flex-col cursor-pointer hover:shadow-md transition relative"
                     @click="page = 'detail'; selectedProduct = 'Robusta Lampung'; selectedPrice = 55000; qty = 1; selectedImg = 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=600&q=80'; selectedDescription = 'Kopi Robusta asli Lampung dengan body tebal, rasa cokelat yang pekat, dan keasaman yang sangat rendah. Di-roast secara merata untuk menjaga kekuatan rasa kopinya.'">
                    <div class="h-44 bg-gray-100 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=400&q=80" alt="Robusta Lampung" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="mb-1"><span class="bg-[#fdf3e7] text-[#c57d38] text-[9px] font-bold px-1.5 py-0.5 rounded">Promo</span></div>
                            <h4 class="font-bold text-gray-800 text-sm mb-0.5">Robusta Lampung</h4>
                            <p class="text-gray-400 text-xs mb-2">Kopi Siger</p>
                        </div>
                        <div>
                            <span class="text-[#c57d38] font-bold text-sm block mb-1">Rp 55.000</span>
                            <div class="flex items-center text-[11px] text-gray-400 gap-1">
                                <span class="text-yellow-500">★</span> 4.7 <span class="mx-1">•</span> 98 terjual
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl overflow-hidden border border-gray-100 flex flex-col cursor-pointer hover:shadow-md transition relative"
                     @click="page = 'detail'; selectedProduct = 'Kintamani Natural'; selectedPrice = 92000; qty = 1; selectedImg = 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=600&q=80'; selectedDescription = 'Kopi Kintamani Bali yang diproses secara natural, menghasilkan karakter rasa sitrus jeruk segar yang dikombinasikan manis karamel alami.'">
                    <div class="h-44 bg-gray-100 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=400&q=80" alt="Kintamani Natural" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="mb-1"><span class="bg-[#eaf7f2] text-[#42b286] text-[9px] font-bold px-1.5 py-0.5 rounded">Baru</span></div>
                            <h4 class="font-bold text-gray-800 text-sm mb-0.5">Kintamani Natural</h4>
                            <p class="text-gray-400 text-xs mb-2">Bali Coffee Co.</p>
                        </div>
                        <div>
                            <span class="text-[#c57d38] font-bold text-sm block mb-1">Rp 92.000</span>
                            <div class="flex items-center text-[11px] text-gray-400 gap-1">
                                <span class="text-yellow-500">★</span> 4.8 <span class="mx-1">•</span> 45 terjual
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl overflow-hidden border border-gray-100 flex flex-col cursor-pointer hover:shadow-md transition"
                     @click="page = 'detail'; selectedProduct = 'Flores Bajawa'; selectedPrice = 78000; qty = 1; selectedImg = 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=600&q=80'; selectedDescription = 'Kopi organik terpopuler dari Bajawa Flores, memiliki sensasi rasa cokelat kacang dengan keharuman bunga (floral aroma) yang legit.'">
                    <div class="h-44 bg-gray-100 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=400&q=80" alt="Flores Bajawa" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm mb-0.5">Flores Bajawa</h4>
                            <p class="text-gray-400 text-xs mb-2">Kopi Ende NTT</p>
                        </div>
                        <div>
                            <span class="text-[#c57d38] font-bold text-sm block mb-1">Rp 78.000</span>
                            <div class="flex items-center text-[11px] text-gray-400 gap-1">
                                <span class="text-yellow-500">★</span> 4.6 <span class="mx-1">•</span> 63 terjual
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 py-6 mb-16">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800 text-base">UMKM unggulan</h3>
                <a href="#" class="text-xs text-[#c57d38] font-medium hover:underline">Lihat semua</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-center gap-4 hover:shadow-sm transition">
                    <div class="w-12 h-12 rounded-full bg-[#cc8444] text-white flex items-center justify-center font-bold text-sm">RA</div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Rumah Kopi Aceh</h4>
                        <p class="text-gray-400 text-xs mb-1">Aceh Tengah</p>
                        <span class="text-[#c57d38] text-[11px] font-medium">12 produk</span>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-center gap-4 hover:shadow-sm transition">
                    <div class="w-12 h-12 rounded-full bg-[#1fa471] text-white flex items-center justify-center font-bold text-sm">KS</div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Kopi Siger</h4>
                        <p class="text-gray-400 text-xs mb-1">Lampung Barat</p>
                        <span class="text-[#c57d38] text-[11px] font-medium">8 produk</span>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-center gap-4 hover:shadow-sm transition">
                    <div class="w-12 h-12 rounded-full bg-[#2c84e4] text-white flex items-center justify-center font-bold text-sm">BC</div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Bali Coffee Co.</h4>
                        <p class="text-gray-400 text-xs mb-1">Kintamani, Bali</p>
                        <span class="text-[#c57d38] text-[11px] font-medium">15 produk</span>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-center gap-4 hover:shadow-sm transition">
                    <div class="w-12 h-12 rounded-full bg-[#846cf4] text-white flex items-center justify-center font-bold text-sm">KE</div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Kopi Ende NTT</h4>
                        <p class="text-gray-400 text-xs mb-1">Flores, NTT</p>
                        <span class="text-[#c57d38] text-[11px] font-medium">9 produk</span>
                    </div>
                </div>
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
                    <img :src="selectedImg" alt="Foto Kopi" class="w-full h-full object-cover">
                </div>
                <div class="grid grid-cols-4 gap-3">
                    <div class="h-20 rounded-xl overflow-hidden border-2 border-[#c57d38] cursor-pointer">
                        <img :src="selectedImg" class="w-full h-full object-cover">
                    </div>
                    <div class="h-20 rounded-xl overflow-hidden border border-gray-200 opacity-60 hover:opacity-100 cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=200&q=80" class="w-full h-full object-cover">
                    </div>
                    <div class="h-20 rounded-xl overflow-hidden border border-gray-200 opacity-60 hover:opacity-100 cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=200&q=80" class="w-full h-full object-cover">
                    </div>
                    <div class="h-20 rounded-xl overflow-hidden border border-gray-200 opacity-60 hover:opacity-100 cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=200&q=80" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex flex-col justify-between">
                <div>
                    <span class="inline-block bg-[#eaf7f2] text-[#42b286] text-xs font-bold px-2.5 py-1 rounded mb-3">Stok ada</span>
                    <h2 class="text-2xl font-extrabold text-gray-800 mb-1" x-text="selectedProduct"></h2>
                    <p class="text-sm text-gray-400 mb-4">250g • Biji kopi • Proses Natural</p>
                    
                    <div class="flex items-baseline gap-3 mb-4">
                        <span class="text-3xl font-black text-[#c57d38]" x-text="'Rp ' + selectedPrice.toLocaleString('id-ID')"></span>
                        <span class="text-sm text-gray-400 line-through">Rp 100.000</span>
                        <span class="bg-red-50 text-red-500 text-xs font-bold px-1.5 py-0.5 rounded">Hemat 15%</span>
                    </div>

                    <div class="flex items-center gap-1 text-sm text-gray-500 mb-6">
                        <span class="text-yellow-500 text-base">★</span> <span class="font-bold text-gray-700">4.9</span> 
                        <span>• 120 ulasan</span> • <span>240 terjual</span>
                    </div>

                    <div class="border-t border-b border-gray-100 py-4 mb-6">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Deskripsi produk</p>
                        <p class="text-sm text-gray-600 leading-relaxed" x-text="selectedDescription"></p>
                    </div>

                    <div class="flex items-center justify-between border border-gray-100 rounded-xl p-4 bg-white mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#cc8444] text-white flex items-center justify-center font-bold text-xs">RA</div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-800">Rumah Kopi Aceh</h4>
                                <p class="text-xs text-gray-400">Aceh Tengah • Respons cepat • ★ 4.9</p>
                            </div>
                        </div>
                        <button class="text-xs border border-gray-200 text-gray-600 font-medium px-3 py-1.5 rounded-lg hover:bg-gray-50">Kunjungi toko</button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500 font-medium">Jumlah</span>
                        <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg p-1.5">
                            <button @click="if(qty > 1) qty--" class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded font-bold hover:bg-gray-100">-</button>
                            <span class="w-6 text-center font-bold text-sm" x-text="qty"></span>
                            <button @click="qty++" class="w-8 h-8 flex items-center justify-center bg-[#c57d38] text-white rounded font-bold hover:bg-[#a66528]">+</button>
                        </div>
                        <span class="text-xs text-gray-400">Stok: 48 pcs</span>
                    </div>

                    <div class="flex gap-3">
                        <button @click="if (@js(auth()->check())) { page = 'cart' } else { window.location.href = '{{ route('login') }}' }" 
                                class="flex-1 border-2 border-[#c57d38] text-[#c57d38] font-bold py-3.5 rounded-xl hover:bg-[#c57d38]/5 transition text-sm">
                            + Tambah ke Keranjang
                        </button>
                        <button @click="if (@js(auth()->check())) { isPaymentOpen = true } else { window.location.href = '{{ route('login') }}' }" 
                                class="flex-1 bg-[#c57d38] text-white font-bold py-3.5 rounded-xl hover:bg-[#a66528] transition shadow-md text-sm">
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

                    <div class="px-6 py-3 border-b border-gray-50 flex items-center gap-2 bg-white">
                        <div class="w-5 h-5 rounded-full bg-[#cc8444] text-white flex items-center justify-center text-[10px] font-bold">RA</div>
                        <span class="text-xs font-bold text-gray-800">Rumah Kopi Aceh</span>
                    </div>

                    <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-white">
                        <input type="checkbox" x-model="cartChecked" class="w-4 h-4 rounded border-gray-300 text-[#c57d38] focus:ring-[#c57d38] mt-4 sm:mt-0">
                        
                        <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                            <img :src="selectedImg" class="w-full h-full object-cover">
                        </div>

                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm leading-tight" x-text="selectedProduct + ' 250g'"></h4>
                            <p class="text-xs text-gray-400 mt-1">Varian: Biji kopi • Roast: Medium</p>
                            <span class="text-[#c57d38] font-extrabold text-sm block mt-2" x-text="'Rp ' + selectedPrice.toLocaleString('id-ID')"></span>
                        </div>

                        <div class="flex items-center gap-4 self-end sm:self-center">
                            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg p-1">
                                <button @click="if(qty > 1) qty--" class="w-7 h-7 flex items-center justify-center bg-white rounded shadow-sm font-bold text-gray-600 hover:bg-gray-100">-</button>
                                <span class="w-6 text-center font-bold text-xs text-gray-800" x-text="qty"></span>
                                <button @click="qty++" class="w-7 h-7 flex items-center justify-center bg-[#c57d38] text-white rounded shadow-sm font-bold hover:bg-[#a66528]">+</button>
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

                    <button class="w-full bg-[#c57d38] text-white font-bold py-4 rounded-xl shadow-md hover:bg-[#a66528] transition text-sm flex items-center justify-center gap-2"
                            :disabled="!cartChecked || isLoading"
                            :class="!cartChecked ? 'opacity-50 cursor-not-allowed' : ''"
                            @click="if (@js(auth()->check())) { isLoading = true; setTimeout(() => { alert('Transaksi Sukses! Silakan periksa halaman pesanan saya.'); page = 'home'; isLoading = false; }, 1500) } else { window.location.href = '{{ route('login') }}' }">
                        <span x-text="isLoading ? 'Memproses Transaksi...' : 'Lanjutkan ke Pembayaran'"></span>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <main x-show="page === 'profile'" x-cloak class="max-w-4xl mx-auto px-6 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 cursor-pointer hover:text-gray-800" @click="page = 'home'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="font-semibold">Kembali ke Beranda</span>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-bold text-gray-800 mb-2">Edit Profil Pengguna</h2>
            <p class="text-xs text-gray-400 mb-6">Perbarui informasi profil Anda untuk keperluan pengiriman dan transaksi yang aman.</p>
            
            <form @submit.prevent="alert('Profil berhasil diperbarui!'); page = 'home'" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" x-model="userProfile.nama" required
                           class="w-full px-4 py-2.5 bg-gray-50 text-sm text-gray-700 rounded-xl focus:outline-none border border-gray-200 focus:border-[#c57d38]/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Alamat Email</label>
                    <input type="email" x-model="userProfile.email" required
                           class="w-full px-4 py-2.5 bg-gray-50 text-sm text-gray-700 rounded-xl focus:outline-none border border-gray-200 focus:border-[#c57d38]/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nomor Telepon</label>
                    <input type="text" x-model="userProfile.telepon" required
                           class="w-full px-4 py-2.5 bg-gray-50 text-sm text-gray-700 rounded-xl focus:outline-none border border-gray-200 focus:border-[#c57d38]/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Alamat Pengiriman</label>
                    <textarea x-model="userProfile.alamat" rows="3" required
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
                        <button class="w-full bg-[#c57d38] text-white font-bold py-3.5 rounded-xl shadow-lg hover:bg-[#a66528] transition flex items-center justify-center gap-2"
                                :disabled="isLoading" 
                                @click="isLoading = true; setTimeout(() => { alert('Pesanan Langsung Berhasil Dibuat! Admin UMKM akan segera memproses pengiriman kopi Anda.'); isPaymentOpen = false; page = 'home'; isLoading = false; }, 1200)">
                            <span x-text="isLoading ? 'Memproses Pesanan...' : 'Saya Sudah Melakukan Pembayaran'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>