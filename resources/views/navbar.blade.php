<nav class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur">
    <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-6">
        <a href="{{ url('/') }}" class="text-5xl font-bold text-amber-500">
            V<span class="text-slate-900">use</span>
        </a>

        <div class="hidden items-center gap-6 text-sm font-medium md:flex">
            <a href="#kategori" class="hover:text-amber-500">Kategori</a>
            <a href="#produk" class="hover:text-amber-500">Produk</a>
            <a href="#keunggulan" class="hover:text-amber-500">Keunggulan</a>
            <a href="{{ url('/contact') }}" class="hover:text-amber-500">Kontak</a>
        </div>

        @guest
            <a href="{{ route('login') }}" class="rounded-full bg-slate-900 p-2 text-white hover:bg-amber-500" title="Login">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M18 12h-9m0 0l3-3m-3 3l3 3" />
                </svg>
            </a>
        @else
            <form action="{{ route('logout') }}" method="POST" class="flex items-center gap-3">
                @csrf
                <span class="hidden text-sm text-slate-700 md:inline">Halo, {{ auth()->user()->name }}</span>
                <button type="submit" class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-amber-500">
                    Logout
                </button>
            </form>
        @endguest
    </div>
</nav>