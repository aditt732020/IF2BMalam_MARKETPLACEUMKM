<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - KopiNusantara')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f9f6f2] text-[#3a2010]">
    <div class="flex min-h-screen">
        <aside class="hidden w-72 bg-gradient-to-b from-[#5a4030] to-[#3a2010] p-6 text-white lg:block">
            <div class="mb-10">
                <p class="text-xs uppercase tracking-[0.2em] text-[#d4b896]">Admin Panel</p>
                <h1 class="mt-2 text-2xl font-bold">KopiNusantara</h1>
            </div>

            <nav class="space-y-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-4 py-3 hover:bg-white/10">Dashboard</a>
                <a href="{{ route('admin.products') }}" class="block rounded-lg px-4 py-3 hover:bg-white/10">Produk</a>
                <a href="{{ route('admin.orders') }}" class="block rounded-lg px-4 py-3 hover:bg-white/10">Pesanan</a>
                <a href="{{ route('admin.sellers') }}" class="block rounded-lg px-4 py-3 hover:bg-white/10">Penjual</a>
            </nav>
        </aside>

        <main class="flex-1">
            <header class="border-b border-[#e7ddd2] bg-white/90 px-6 py-4 backdrop-blur">
                <div class="mx-auto flex w-full max-w-6xl items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-[#9a8070]">Marketplace UMKM</p>
                        <h2 class="text-xl font-bold">@yield('header', 'Dashboard Admin')</h2>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-lg bg-[#c97e3a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#a06020]">
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <section class="mx-auto w-full max-w-6xl px-6 py-8">
                @yield('content')
            </section>
        </main>
    </div>
</body>
</html>
