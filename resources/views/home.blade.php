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
        .hero-gradient { background: linear-gradient(135deg, #3a2010 0%, #5a4030 100%); }
        .product-card:hover { transform: translateY(-5px); transition: all 0.3s ease; }
    </style>
</head>
<body class="bg-[#f9f6f2]" x-data="{ 
    isPaymentOpen: false, 
    isLoading: false,
    selectedProduct: '', 
    selectedPrice: '',
    selectedDescription: '',
    qty: 1,
    size: 'Medium',
    sugar: 'Normal',
    temp: 'Panas',
    paymentMethod: 'QRIS',
    searchQuery: '',
    
    calculateTotal() {
        let price = parseInt(this.selectedPrice.replace(/[^0-9]/g, '')) || 0;
        return (price * this.qty).toLocaleString('id-ID');
    },

    async processPayment() {
        this.isLoading = true;
        
        // Data yang dikirim ke backend
        const orderData = {
            product_name: this.selectedProduct,
            price: this.selectedPrice,
            quantity: this.qty,
            size: this.size,
            sugar: this.sugar,
            temp: this.temp,
            payment_method: this.paymentMethod,
            total_price: 'Rp' + this.calculateTotal(),
            status: 'pending' // Status awal di dashboard admin
        };

        try {
            // Simulasi pengiriman data ke Route Laravel (Ganti URL sesuai route Anda)
            // const response = await fetch('/api/orders', {
            //     method: 'POST',
            //     headers: { 
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            //     },
            //     body: JSON.stringify(orderData)
            // });

            // Simulasi Delay Loading
            await new Promise(resolve => setTimeout(resolve, 1500));

            alert('Pesanan Berhasil!\nSilahkan lakukan pembayaran melalui ' + this.paymentMethod);
            this.isPaymentOpen = false;
        } catch (error) {
            alert('Terjadi kesalahan saat memproses pesanan.');
        } finally {
            this.isLoading = false;
        }
    }
}">
    
    <nav class="bg-white/90 backdrop-blur-sm sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#c97e3a] rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">K</span>
                    </div>
                    <div>
                        <h1 class="text-[#3a2010] font-bold text-lg">KopiNusantara</h1>
                        <p class="text-[#9a8070] text-xs">Marketplace UMKM Kopi</p>
                    </div>
                </div>
                
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input type="text" x-model="searchQuery" placeholder="Cari kopi..." 
                               class="w-full px-4 py-2 pl-10 border border-[#e0d8cc] rounded-lg focus:outline-none focus:border-[#c97e3a]">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#9a8070]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf 
                        <button type="submit" class="text-[#9a8070] hover:text-[#c97e3a] text-sm transition">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <section class="hero-gradient text-white py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center lg:text-left">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                <div class="flex-1">
                    <div class="inline-block px-3 py-1 bg-[#c97e3a]/20 rounded-full text-[#c97e3a] text-sm mb-4">100% Kopi Lokal</div>
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4">Temukan kopi terbaik dari UMKM pilihan</h2>
                    <p class="text-[#d4b896] text-lg mb-8 max-w-lg mx-auto lg:mx-0">Dukung pelaku usaha kopi nusantara langsung dari aplikasi.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <button class="bg-[#c97e3a] hover:bg-[#a06020] px-8 py-3 rounded-lg font-semibold transition">Belanja Sekarang</button>
                    </div>
                </div>
                <div class="flex-1">
                    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=500&h=400&fit=crop" class="rounded-2xl shadow-2xl w-full max-w-md mx-auto">
                </div>
            </div>
        </div>
    </section>
    
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-2xl font-bold text-[#3a2010] mb-8">Produk kami</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($products as $product)
                    <div class="bg-white rounded-xl overflow-hidden shadow-sm product-card cursor-pointer group"
                         x-show="'{{ strtolower($product->name) }}'.includes(searchQuery.toLowerCase())"
                         @click="isPaymentOpen = true; 
                                 selectedProduct = '{{ e($product->name) }}'; 
                                 selectedPrice = 'Rp{{ number_format($product->price, 0, ',', '.') }}'; 
                                 selectedDescription = '{{ e($product->description ?? 'Biji kopi pilihan yang diproses dengan teknik tradisional untuk rasa yang otentik.') }}';
                                 qty = 1">
                        <div class="relative h-48 bg-gradient-to-br from-[#8B6914] to-[#5a4030] flex items-center justify-center">
                            <span class="text-6xl">☕</span>
                            <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <span class="bg-white text-[#3a2010] px-4 py-2 rounded-full font-bold shadow-lg">Beli</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-[#3a2010]">{{ $product->name }}</h4>
                            <p class="text-[#9a8070] text-sm">{{ \Illuminate\Support\Str::limit($product->description ?? 'Produk UMKM', 45) }}</p>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-[#c97e3a] font-bold">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-[#9a8070]">Stok: {{ $product->stock }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-xl p-12 shadow-sm text-center text-[#9a8070]">Belum ada produk tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Modal Konfigurasi & Payment -->
    <div x-show="isPaymentOpen" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-sm" @click="isPaymentOpen = false"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-[#3a2010] px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="text-lg font-bold">Konfigurasi Pesanan</h3>
                    <button @click="isPaymentOpen = false" class="text-white/80 hover:text-white">✕</button>
                </div>
                
                <div class="px-6 py-6 space-y-6">
                    <!-- Deskripsi Produk -->
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <p class="text-xs text-[#9a8070] uppercase font-bold mb-1">Produk</p>
                                <span class="text-[#3a2010] font-bold text-lg block leading-tight" x-text="selectedProduct"></span>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-[#9a8070] uppercase font-bold mb-2">Jumlah</p>
                                <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg p-1">
                                    <button @click="if(qty > 1) qty--" class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded hover:bg-gray-200 font-bold">-</button>
                                    <span class="w-6 text-center font-bold" x-text="qty"></span>
                                    <button @click="qty++" class="w-8 h-8 flex items-center justify-center bg-[#c97e3a] text-white rounded hover:bg-[#a06020] font-bold">+</button>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-[#5a4030] leading-relaxed italic mb-3" x-text="selectedDescription"></p>
                        <span class="text-[#c97e3a] font-extrabold text-xl" x-text="selectedPrice"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-[#9a8070] uppercase font-bold">Ukuran</label>
                            <select x-model="size" class="w-full mt-1 border border-gray-200 rounded-lg p-2 focus:ring-1 focus:ring-[#c97e3a] outline-none text-sm bg-white">
                                <option>Small</option><option>Medium</option><option>Large</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs text-[#9a8070] uppercase font-bold">Suhu</label>
                            <select x-model="temp" class="w-full mt-1 border border-gray-200 rounded-lg p-2 focus:ring-1 focus:ring-[#c97e3a] outline-none text-sm bg-white">
                                <option>Panas</option><option>Dingin (Es)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-[#9a8070] uppercase font-bold">Gula</label>
                        <div class="flex gap-2 mt-2">
                            <template x-for="g in ['No Sugar', 'Less', 'Normal', 'Extra']">
                                <button @click="sugar = g" :class="sugar === g ? 'bg-[#c97e3a] text-white' : 'bg-white text-gray-500 border-gray-200'"
                                    class="flex-1 py-2 text-xs font-semibold border rounded-lg transition" x-text="g"></button>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-[#9a8070] uppercase font-bold">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-3 mt-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="pay" value="QRIS" x-model="paymentMethod" class="hidden peer">
                                <div class="p-3 text-center border rounded-lg peer-checked:border-[#c97e3a] peer-checked:bg-[#c97e3a]/5 text-sm font-medium transition">QRIS</div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="pay" value="Transfer" x-model="paymentMethod" class="hidden peer">
                                <div class="p-3 text-center border rounded-lg peer-checked:border-[#c97e3a] peer-checked:bg-[#c97e3a]/5 text-sm font-medium transition">Transfer</div>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-dashed border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-[#3a2010] font-medium">Total Bayar</span>
                            <span class="text-2xl font-black text-[#c97e3a]" x-text="'Rp' + calculateTotal()"></span>
                        </div>
                        <button class="w-full bg-[#c97e3a] text-white font-bold py-4 rounded-xl shadow-lg hover:bg-[#a06020] transition active:scale-95 flex items-center justify-center gap-2"
                                :disabled="isLoading"
                                @click="processPayment()">
                            <svg x-show="isLoading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span x-text="isLoading ? 'Memproses...' : 'Konfirmasi & Bayar'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="bg-[#3a2010] text-white py-12 mt-16 text-center md:text-left">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="font-bold text-lg">KopiNusantara</h1>
                    <p class="text-[#d4b896] text-sm">© 2026 KopiNusantara - Mendukung UMKM Indonesia</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>