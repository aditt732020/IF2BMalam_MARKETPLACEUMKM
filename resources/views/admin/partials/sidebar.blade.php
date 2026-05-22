@php
    $navItems = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'chart'],
        ['route' => 'admin.products', 'label' => 'Produk Kopi', 'icon' => 'box'],
        ['route' => 'admin.orders', 'label' => 'Pesanan', 'icon' => 'cart'],
        ['route' => 'admin.buyers', 'label' => 'Kelola Pembeli', 'icon' => 'users'],
        ['route' => 'admin.sellers', 'label' => 'UMKM Penjual', 'icon' => 'store'],
    ];
@endphp

<aside class="flex h-full w-72 flex-col bg-gradient-to-b from-[#3a2010] via-[#4a2e1a] to-[#2d1608] text-white">
    {{-- Bagian Brand Header (Sudah diganti dengan Logo baru Anda) --}}
    <div class="border-b border-white/10 px-6 py-5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('image/logo3.png') }}" alt="Logo Kopi Nusantara" class="h-20 w-auto object-contain">
            
            {{-- Teks kecil di samping logo (Opsional, silakan hapus block div di bawah ini jika ingin logo saja) --}}
            <div>
                <h1 class="text-sm font-bold leading-tight text-white">Kopi Nusantara</h1>
                <p class="text-[10px] tracking-wide text-[#d4b896]">Admin Panel</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 space-y-1 px-4 py-5">
        <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-[#d4b896]/70">Menu Utama</p>
        @foreach ($navItems as $item)
            @php $active = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition {{ $active ? 'bg-[#c57d38] text-white shadow-md shadow-[#c57d38]/20' : 'text-[#e8d5c0] hover:bg-white/10' }}">
                @include('admin.partials.icons.' . $item['icon'])
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="border-t border-white/10 p-4 space-y-2">
        <a href="{{ route('home') }}" target="_blank"
           class="flex items-center gap-3 rounded-xl border border-white/15 px-4 py-3 text-sm text-[#e8d5c0] transition hover:bg-white/10">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Lihat Toko Pembeli
        </a>
        <div class="rounded-xl bg-white/5 px-4 py-3">
            <p class="text-[10px] text-[#d4b896]">Masuk sebagai</p>
            <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
            <p class="truncate text-xs text-[#d4b896]/80">{{ auth()->user()->email }}</p>
        </div>
    </div>
</aside>