@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            {{-- Previous Page Link (mobile) --}}
            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-bone-faint bg-ink-raised/50 border border-line cursor-default rounded-lg">
                    <i class="fas fa-chevron-left mr-1"></i> Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-bone-dim bg-ink-raised/50 border border-line rounded-lg hover:text-gold hover:border-gold/50 transition-colors">
                    <i class="fas fa-chevron-left mr-1"></i> Previous
                </a>
            @endif

            {{-- Next Page Link (mobile) --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-bone-dim bg-ink-raised/50 border border-line rounded-lg hover:text-gold hover:border-gold/50 transition-colors">
                    Next <i class="fas fa-chevron-right ml-1"></i>
                </a>
            @else
                <span
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-bone-faint bg-ink-raised/50 border border-line cursor-default rounded-lg">
                    Next <i class="fas fa-chevron-right ml-1"></i>
                </span>
            @endif
        </div>

        {{-- Desktop Pagination --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-bone-dim">
                    Showing <span class="font-medium text-bone">{{ $paginator->firstItem() }}</span>
                    – <span class="font-medium text-bone">{{ $paginator->lastItem() }}</span>
                    of <span class="font-medium text-gold">{{ $paginator->total() }}</span>
                </p>
            </div>

            <div>
                <span
                    class="relative z-0 inline-flex shadow-sm rounded-xl overflow-hidden border border-line bg-ink-raised/50 backdrop-blur-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Previous"
                            class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-bone-faint bg-transparent border-r border-line cursor-default">
                            <i class="fas fa-chevron-left w-4 h-4"></i>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                            class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-bone-dim bg-transparent border-r border-line hover:text-gold hover:bg-ink-raised2/50 transition-colors"
                            aria-label="Previous">
                            <i class="fas fa-chevron-left w-4 h-4"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-bone-faint bg-transparent border-r border-line">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-ink bg-gold border-r border-gold-dim">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-bone-dim bg-transparent border-r border-line hover:text-gold hover:bg-ink-raised2/50 transition-colors"
                                        aria-label="Go to page {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                            class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-bone-dim bg-transparent hover:text-gold hover:bg-ink-raised2/50 transition-colors"
                            aria-label="Next">
                            <i class="fas fa-chevron-right w-4 h-4"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Next"
                            class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-bone-faint bg-transparent cursor-default">
                            <i class="fas fa-chevron-right w-4 h-4"></i>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
