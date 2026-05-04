<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - KopiNusantara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f9f6f2]">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-6xl w-full bg-[#f9f6f2] rounded-2xl overflow-hidden">
            <div class="flex flex-col lg:flex-row">
                
                <!-- Sisi Kiri - Hero Section -->
                <div class="flex-1 bg-gradient-to-br from-[#5a4030] to-[#3a2010] p-8 lg:p-12 rounded-2xl lg:rounded-r-none relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-[#c97e3a]/10 rounded-full blur-3xl"></div>
                    
                    <!-- Logo -->
                    <div class="flex items-center gap-3 mb-12">
                        <div class="w-10 h-10 bg-[#c97e3a] rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">K</span>
                        </div>
                        <div>
                            <h1 class="text-white font-bold text-lg">KopiNusantara</h1>
                            <p class="text-[#d4b896] text-xs">Marketplace UMKM Kopi</p>
                        </div>
                    </div>
                    
                    <!-- Main Text -->
                    <div class="mb-8">
                        <h2 class="text-white text-3xl lg:text-4xl font-bold leading-tight mb-4">
                            Dukung petani kopi<br>lokal Indonesia
                        </h2>
                        <p class="text-[#d4b896] text-sm">
                            Bergabung bersama ribuan pembeli yang<br>mendukung UMKM kopi nusantara.
                        </p>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 mb-12">
                        <div>
                            <p class="text-[#c97e3a] text-2xl font-bold">2.400+</p>
                            <p class="text-[#d4b896] text-xs">UMKM terdaftar</p>
                        </div>
                        <div>
                            <p class="text-[#c97e3a] text-2xl font-bold">18 rb+</p>
                            <p class="text-[#d4b896] text-xs">Produk tersedia</p>
                        </div>
                        <div>
                            <p class="text-[#c97e3a] text-2xl font-bold">34 prov</p>
                            <p class="text-[#d4b896] text-xs">Daerah asal kopi</p>
                        </div>
                    </div>
                    
                    <p class="text-[#a08060] text-xs absolute bottom-8 left-8 lg:left-12">
                        © 2026 KopiNusantara - Mendukung UMKM Indonesia
                    </p>
                </div>
                
                <!-- Sisi Kanan - Form Register -->
                <div class="flex-1 p-8 lg:p-12">
                    <!-- Toggle Buttons -->
                    <div class="flex gap-4 mb-8">
                        <a href="{{ route('login') }}" class="text-[#9a8070] pb-2 hover:text-[#3a2010] transition">Masuk</a>
                        <a href="#" class="text-[#3a2010] font-semibold border-b-2 border-[#c97e3a] pb-2">Daftar</a>
                    </div>
                    
                    <!-- Form Register -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf
                        
                        <div>
                            <h2 class="text-[#3a2010] text-2xl font-bold mb-2">Selamat datang!</h2>
                            <p class="text-[#9a8070] text-sm">Daftar untuk melanjutkan belanja kopi pilihan</p>
                        </div>
                        
                        <!-- Name Input -->
                        <div>
                            <label class="block text-[#7a6050] text-sm mb-2">Nama lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                   class="w-full px-4 py-3 border border-[#e0d8cc] rounded-lg focus:outline-none focus:border-[#c97e3a] transition @error('name') border-red-500 @enderror"
                                   placeholder="Nama Anda">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Input -->
                        <div>
                            <label class="block text-[#7a6050] text-sm mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                   class="w-full px-4 py-3 border border-[#e0d8cc] rounded-lg focus:outline-none focus:border-[#c97e3a] transition @error('email') border-red-500 @enderror"
                                   placeholder="nama@email.com">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password Input -->
                        <div>
                            <label class="block text-[#7a6050] text-sm mb-2">Kata sandi</label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                       class="w-full px-4 py-3 border border-[#e0d8cc] rounded-lg focus:outline-none focus:border-[#c97e3a] transition @error('password') border-red-500 @enderror"
                                       placeholder="*********">
                                <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#9a8070] hover:text-[#3a2010]">
                                    👁️
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Confirm Password Input -->
                        <div>
                            <label class="block text-[#7a6050] text-sm mb-2">Ulangi Kata sandi</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="w-full px-4 py-3 border border-[#e0d8cc] rounded-lg focus:outline-none focus:border-[#c97e3a] transition"
                                       placeholder="*********">
                                <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#9a8070] hover:text-[#3a2010]">
                                    👁️
                                </button>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full bg-[#c97e3a] hover:bg-[#a06020] text-white font-semibold py-3 rounded-lg transition duration-200 mt-4">
                            Daftar Sekarang
                        </button>
                        
                        <!-- Login Link -->
                        <p class="text-center text-[#9a8070] text-sm">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-[#c97e3a] hover:underline font-semibold">Masuk sekarang</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Fungsi reusable untuk toggle password
        function setupPasswordToggle(buttonId, inputId) {
            const button = document.querySelector(buttonId);
            const input = document.querySelector(inputId);
            if (button && input) {
                button.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
                });
            }
        }

        setupPasswordToggle('#togglePassword', '#password');
        setupPasswordToggle('#toggleConfirmPassword', '#password_confirmation');
    </script>
</body>
</html>