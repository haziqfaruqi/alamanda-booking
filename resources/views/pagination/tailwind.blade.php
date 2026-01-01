@if ($paginator->hasPages())
    <nav aria-label="Page navigation" class="flex items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-zinc-400 bg-white border border-zinc-300 rounded-xl cursor-not-allowed">
                Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 rounded-xl hover:bg-zinc-50 transition-colors">
                Previous
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 rounded-xl">
                    {{ $element }}
                </span>

            {{-- Array Of Links --}}
            @elseif (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-indigo-600 rounded-xl">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 rounded-xl hover:bg-zinc-50 transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 rounded-xl hover:bg-zinc-50 transition-colors">
                Next
            </a>
        @else
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-zinc-400 bg-white border border-zinc-300 rounded-xl cursor-not-allowed">
                Next
            </span>
        @endif
    </nav>
@endif
