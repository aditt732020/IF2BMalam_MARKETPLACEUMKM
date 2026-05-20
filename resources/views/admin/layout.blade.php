<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - KopiNusantara')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-[#fdf9f4] text-[#3a2010]" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        {{-- Desktop sidebar --}}
        <div class="hidden lg:block lg:fixed lg:inset-y-0 lg:z-40">
            @include('admin.partials.sidebar')
        </div>

        {{-- Mobile sidebar overlay --}}
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
            <div class="absolute inset-0 bg-black/50" @click="sidebarOpen = false"></div>
            <div class="absolute inset-y-0 left-0" @click.away="sidebarOpen = false">
                @include('admin.partials.sidebar')
            </div>
        </div>

        <div class="flex flex-1 flex-col lg:pl-72">
            <header class="sticky top-0 z-30 border-b border-[#e7ddd2]/80 bg-white/90 px-4 py-4 backdrop-blur-md sm:px-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = true" class="rounded-lg border border-[#e7ddd2] p-2 text-[#5a4030] hover:bg-[#f5eee7] lg:hidden">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#c57d38]">Kopi Nusantara</p>
                            <h2 class="text-lg font-bold text-[#3a2010] sm:text-xl">@yield('header', 'Dashboard Admin')</h2>
                            @hasSection('subtitle')
                                <p class="text-xs text-[#9a8070]">@yield('subtitle')</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        <a href="{{ route('home') }}" target="_blank"
                           class="hidden rounded-xl border border-[#e7ddd2] px-3 py-2 text-xs font-semibold text-[#5a4030] transition hover:bg-[#f5eee7] sm:inline-flex sm:items-center sm:gap-1.5">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Toko
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded-xl bg-[#c57d38] px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-[#a66528] sm:text-sm">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 sm:py-8">
                <div class="mx-auto w-full max-w-7xl">
                    @yield('content')
                </div>
            </main>

            <footer class="border-t border-[#e7ddd2]/60 px-6 py-4 text-center text-xs text-[#9a8070]">
                &copy; {{ date('Y') }} KopiNusantara · Marketplace UMKM Kopi Indonesia
            </footer>
        </div>
    </div>
</body>
</html>
