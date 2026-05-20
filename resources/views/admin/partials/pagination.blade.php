@if ($paginator->hasPages())
    <nav class="mt-4 flex justify-center rounded-2xl border border-[#e7ddd2] bg-white px-4 py-4 sm:px-6" aria-label="Navigasi halaman">
        <div class="flex flex-wrap items-center justify-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="inline-flex cursor-not-allowed items-center gap-1 rounded-xl border border-[#e7ddd2] bg-[#f5eee7]/50 px-3 py-2 text-xs font-semibold text-[#9a8070]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="inline-flex items-center gap-1 rounded-xl border border-[#d8c7b8] bg-white px-3 py-2 text-xs font-semibold text-[#3a2010] transition hover:border-[#c57d38] hover:bg-[#fdf3e7]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Sebelumnya
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-xs text-[#9a8070]">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl bg-[#c57d38] px-3 text-xs font-bold text-white shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-[#e7ddd2] bg-white px-3 text-xs font-semibold text-[#5a4030] transition hover:border-[#c57d38] hover:bg-[#fdf3e7]">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="inline-flex items-center gap-1 rounded-xl border border-[#c57d38] bg-[#c57d38] px-3 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-[#a66528]">
                    Selanjutnya
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="inline-flex cursor-not-allowed items-center gap-1 rounded-xl border border-[#e7ddd2] bg-[#f5eee7]/50 px-3 py-2 text-xs font-semibold text-[#9a8070]">
                    Selanjutnya
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
