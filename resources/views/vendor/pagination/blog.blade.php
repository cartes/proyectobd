@if ($paginator->hasPages())
    @php
        $isDark = ($theme ?? 'dark') === 'dark';
        $textClass = $isDark ? 'text-slate-300' : 'text-gray-700';
        $btnNormal = $isDark ? 'bg-slate-800/50 border border-slate-700 hover:bg-pink-500/20 hover:border-pink-500 text-slate-300 transition-all font-medium' : 'bg-white border border-gray-200 hover:bg-amber-50 hover:border-amber-500 hover:text-amber-600 font-medium transition-all shadow-sm';
        $btnActive = $isDark ? 'bg-gradient-to-r from-pink-500 to-rose-600 text-white border border-transparent shadow-lg shadow-pink-500/20 font-bold' : 'bg-gradient-to-r from-amber-500 to-orange-600 text-white border border-transparent shadow-md shadow-amber-500/20 font-bold';
        $btnDisabled = $isDark ? 'bg-slate-900 border border-slate-800 text-slate-600 cursor-not-allowed' : 'bg-gray-50 border border-gray-100 text-gray-400 cursor-not-allowed';
    @endphp

    <nav role="navigation" aria-label="Navegación de Paginación" class="flex justify-center mt-12 mb-8">
        <ul class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="flex items-center justify-center w-11 h-11 rounded-xl {{ $btnDisabled }}" aria-disabled="true" aria-label="Anterior">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center justify-center w-11 h-11 rounded-xl {{ $btnNormal }}" aria-label="Anterior">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li><span class="flex items-center justify-center w-11 h-11 {{ $textClass }} tracking-widest">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="flex items-center justify-center w-11 h-11 rounded-xl {{ $btnActive }} transform scale-105">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" class="flex items-center justify-center w-11 h-11 rounded-xl {{ $btnNormal }} hover:-translate-y-1">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex items-center justify-center w-11 h-11 rounded-xl {{ $btnNormal }}" aria-label="Siguiente">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </li>
            @else
                <li>
                    <span class="flex items-center justify-center w-11 h-11 rounded-xl {{ $btnDisabled }}" aria-disabled="true" aria-label="Siguiente">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
