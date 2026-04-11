@extends('app')

@section('title', 'Landing Page Marketplace UMKM')

@section('content')
<section class="hero-bg flex min-h-[88vh] items-center text-white">
    <div class="mx-auto w-full max-w-7xl px-6 py-16">
        <div class="max-w-3xl">
            <p class="mb-4 inline-block rounded-full bg-white/20 px-4 py-1 text-sm font-semibold">
                Platform Digital untuk UMKM Indonesia
            </p>
            <h1 class="text-4xl font-extrabold leading-tight md:text-6xl">
                Belanja Produk Lokal, Bantu UMKM Naik Kelas
            </h1>
            <p class="mt-5 text-lg text-slate-100 md:text-xl">
                Temukan produk berkualitas langsung dari pelaku UMKM terpercaya di seluruh Indonesia.
                Dari kuliner, fesyen, kerajinan, hingga kebutuhan rumah tangga. MEMEKKKK
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#produk" class="rounded-full bg-amber-400 px-7 py-3 font-semibold text-slate-900 hover:bg-amber-300">
                    Jelajahi Produk
                </a>
            </div>
            <div class="mt-10 grid max-w-2xl grid-cols-2 gap-4 text-sm md:grid-cols-3">
                <div class="rounded-xl bg-white/15 p-3">10.000+ UMKM Aktif</div>
                <div class="rounded-xl bg-white/15 p-3">150.000+ Produk</div>
                <div class="rounded-xl bg-white/15 p-3">Layanan Seluruh Indonesia</div>
            </div>
        </div>
    </div>
</section>

<section id="kategori" class="py-16">
    <div class="mx-auto w-full max-w-7xl px-6">
        <div class="mb-10 text-center">
            <h2 class="text-3xl font-bold md:text-4xl">Kategori Populer</h2>
            <p class="mt-3 text-slate-600">Pilih kategori favorit dan temukan produk terbaik.</p>
        </div>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
            @foreach(['Fashion', 'Makanan', 'Kerajinan', 'Kecantikan', 'Pertanian', 'Dekorasi'] as $kategori)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-xl">🛍️</div>
                    <h3 class="font-semibold">{{ $kategori }}</h3>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="produk" class="bg-white py-16">
    <div class="mx-auto w-full max-w-7xl px-6">
        <div class="mb-10 flex items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold md:text-4xl">Produk Unggulan</h2>
                <p class="mt-2 text-slate-600">Pilihan terbaik dari para pelaku UMKM.</p>
            </div>
            <a href="#" class="text-sm font-semibold text-amber-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @for ($i = 1; $i <= 8; $i++)
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:border-amber-400 hover:shadow-lg">
                    <div class="flex h-44 items-center justify-center bg-slate-100 text-5xl">🎁</div>
                    <div class="p-4">
                        <p class="text-xs font-medium text-emerald-600">Terjual {{ 100 + ($i * 7) }}</p>
                        <h3 class="mt-2 line-clamp-2 font-semibold">Produk UMKM Pilihan #{{ $i }}</h3>
                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-lg font-bold text-slate-900">Rp{{ number_format(45000 + ($i * 5000), 0, ',', '.') }}</p>
                            <button class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-500">
                                + Keranjang
                            </button>
                        </div>
                    </div>
                </article>
            @endfor
        </div>
    </div>
</section>

<section id="keunggulan" class="bg-amber-50 py-16">
    <div class="mx-auto grid w-full max-w-7xl gap-6 px-6 md:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-3xl">🇮🇩</p>
            <h3 class="mt-4 text-xl font-semibold">100% UMKM Lokal</h3>
            <p class="mt-2 text-sm text-slate-600">Setiap transaksi membantu pertumbuhan ekonomi lokal.</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-3xl">🔒</p>
            <h3 class="mt-4 text-xl font-semibold">Transaksi Aman</h3>
            <p class="mt-2 text-sm text-slate-600">Sistem pembayaran dirancang nyaman dan tepercaya.</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-3xl">🚚</p>
            <h3 class="mt-4 text-xl font-semibold">Pengiriman Luas</h3>
            <p class="mt-2 text-sm text-slate-600">Dukungan pengiriman ke berbagai daerah Indonesia.</p>
        </div>
    </div>
</section>

@endsection