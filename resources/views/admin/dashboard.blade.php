@extends('admin.layout')

@section('title', 'Dashboard Admin - KopiNusantara')
@section('header', 'Dashboard Admin')
@section('subtitle', 'Pantau performa marketplace kopi UMKM nusantara')

@section('content')
    {{-- Hero banner --}}
    <div class="mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-[#f5ebd9] to-[#ede0c8] p-6 sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="inline-block rounded-full bg-white/60 px-3 py-1 text-xs font-semibold text-[#936232]">100% Kopi Lokal Indonesia</span>
                <h3 class="mt-3 text-xl font-extrabold text-[#3a1f0b] sm:text-2xl">Selamat datang, {{ auth()->user()->name }}!</h3>
                <p class="mt-1 max-w-lg text-sm text-[#5a4030]/80">Kelola produk, pesanan, pembeli, dan UMKM penjual dari satu panel terpusat.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.products') }}" class="rounded-xl bg-[#c57d38] px-4 py-2.5 text-xs font-bold text-white shadow-md transition hover:bg-[#a66528]">+ Tambah Produk</a>
                <a href="{{ route('admin.orders') }}" class="rounded-xl border border-[#c57d38] bg-white/50 px-4 py-2.5 text-xs font-bold text-[#c57d38] transition hover:bg-white">Lihat Pesanan</a>
            </div>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#9a8070]">Total Pendapatan</p>
                    <p class="mt-2 text-2xl font-extrabold text-[#3a2010]">Rp{{ number_format($stats['totalRevenue'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-[#9a8070]">Dari pesanan dibayar & selesai</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fdf3e7] text-[#c57d38]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#9a8070]">Total Pesanan</p>
                    <p class="mt-2 text-2xl font-extrabold text-[#3a2010]">{{ $stats['totalOrders'] }}</p>
                    <p class="mt-1 text-xs text-amber-600 font-medium">{{ $stats['pendingOrders'] }} menunggu pembayaran</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#9a8070]">Produk Aktif</p>
                    <p class="mt-2 text-2xl font-extrabold text-[#3a2010]">{{ $stats['activeProducts'] }}<span class="text-base font-medium text-[#9a8070]"> / {{ $stats['totalProducts'] }}</span></p>
                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $stats['lowStockCount'] }} stok menipis</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#9a8070]">Pengguna</p>
                    <p class="mt-2 text-2xl font-extrabold text-[#3a2010]">{{ $stats['totalBuyers'] + $stats['totalSellers'] }}</p>
                    <p class="mt-1 text-xs text-[#9a8070]">{{ $stats['totalBuyers'] }} pembeli · {{ $stats['totalSellers'] }} UMKM</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Status pesanan --}}
        <div class="rounded-2xl border border-[#e7ddd2] bg-white p-6 shadow-sm lg:col-span-1">
            <h3 class="mb-4 text-sm font-bold text-[#3a2010]">Status Pesanan</h3>
            <div class="space-y-3">
                @foreach ($ordersByStatus as $item)
                    @php
                        $pct = $stats['totalOrders'] > 0 ? round(($item['count'] / $stats['totalOrders']) * 100) : 0;
                    @endphp
                    <div>
                        <div class="mb-1 flex justify-between text-xs">
                            <span class="font-medium text-[#5a4030]">{{ $item['label'] }}</span>
                            <span class="font-bold text-[#3a2010]">{{ $item['count'] }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-[#f3ebe3]">
                            <div class="h-full rounded-full bg-[#c57d38] transition-all" style="width: {{ max($pct, $item['count'] > 0 ? 4 : 0) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pesanan terbaru --}}
        <div class="rounded-2xl border border-[#e7ddd2] bg-white shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between border-b border-[#f0e6dc] px-6 py-4">
                <h3 class="text-sm font-bold text-[#3a2010]">Pesanan Terbaru</h3>
                <a href="{{ route('admin.orders') }}" class="text-xs font-semibold text-[#c57d38] hover:underline">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-[#fdf9f4] text-left text-xs uppercase tracking-wider text-[#9a8070]">
                        <tr>
                            <th class="px-6 py-3 font-semibold">ID</th>
                            <th class="px-6 py-3 font-semibold">Pembeli</th>
                            <th class="px-6 py-3 font-semibold">Produk</th>
                            <th class="px-6 py-3 font-semibold">Total</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0e6dc]">
                        @forelse ($recentOrders as $order)
                            <tr class="hover:bg-[#fdf9f4]/50">
                                <td class="px-6 py-3 font-medium">#{{ $order->id }}</td>
                                <td class="px-6 py-3">{{ $order->buyer->name ?? '-' }}</td>
                                <td class="px-6 py-3">{{ Str::limit($order->product->name ?? '-', 25) }}</td>
                                <td class="px-6 py-3 font-semibold text-[#c57d38]">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-3">@include('admin.partials.status-badge', ['order' => $order])</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-[#9a8070]">Belum ada pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Stok menipis + aksi cepat --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-[#e7ddd2] bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#3a2010]">Stok Menipis</h3>
                <span class="rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-bold text-red-600">{{ $stats['lowStockCount'] }} produk</span>
            </div>
            @forelse ($lowStockProducts as $product)
                <div class="flex items-center justify-between border-b border-[#f0e6dc] py-3 last:border-0">
                    <div>
                        <p class="text-sm font-semibold text-[#3a2010]">{{ $product->name }}</p>
                        <p class="text-xs text-[#9a8070]">{{ $product->shop_name ?? 'Tanpa toko' }}</p>
                    </div>
                    <span class="rounded-lg bg-red-50 px-2 py-1 text-xs font-bold text-red-600">{{ $product->stock }} unit</span>
                </div>
            @empty
                <p class="text-sm text-[#9a8070]">Semua stok produk aman.</p>
            @endforelse
        </div>

        <div class="rounded-2xl border border-[#e7ddd2] bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-bold text-[#3a2010]">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.products') }}" class="flex flex-col items-center gap-2 rounded-xl border border-[#e7ddd2] p-4 text-center transition hover:border-[#c57d38] hover:bg-[#fdf3e7]">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fdf3e7] text-[#c57d38]">@include('admin.partials.icons.box')</div>
                    <span class="text-xs font-bold text-[#3a2010]">Kelola Produk</span>
                </a>
                <a href="{{ route('admin.orders') }}" class="flex flex-col items-center gap-2 rounded-xl border border-[#e7ddd2] p-4 text-center transition hover:border-[#c57d38] hover:bg-[#fdf3e7]">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fdf3e7] text-[#c57d38]">@include('admin.partials.icons.cart')</div>
                    <span class="text-xs font-bold text-[#3a2010]">Kelola Pesanan</span>
                </a>
                <a href="{{ route('admin.buyers') }}" class="flex flex-col items-center gap-2 rounded-xl border border-[#e7ddd2] p-4 text-center transition hover:border-[#c57d38] hover:bg-[#fdf3e7]">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fdf3e7] text-[#c57d38]">@include('admin.partials.icons.users')</div>
                    <span class="text-xs font-bold text-[#3a2010]">Kelola Pembeli</span>
                </a>
                <a href="{{ route('admin.sellers') }}" class="flex flex-col items-center gap-2 rounded-xl border border-[#e7ddd2] p-4 text-center transition hover:border-[#c57d38] hover:bg-[#fdf3e7]">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fdf3e7] text-[#c57d38]">@include('admin.partials.icons.store')</div>
                    <span class="text-xs font-bold text-[#3a2010]">Kelola UMKM</span>
                </a>
            </div>

            <div class="mt-5 rounded-xl bg-[#f5ebd9]/50 p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-[#936232]">Kategori di toko pembeli</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach (\App\Models\Product::categories() as $key => $label)
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-[#5a4030] shadow-sm">{{ $label }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
