@if ($paginator->hasPages())
    <div class="flex gap-1">
        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="px-2 py-1 text-xs text-gray-600 bg-white/5 rounded">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-2 py-1 text-xs text-gray-400 bg-white/5 rounded">‹</a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span>…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-2 py-1 text-xs bg-teal-500 text-white rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-2 py-1 text-xs text-gray-400 bg-white/5 rounded">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-2 py-1 text-xs text-gray-400 bg-white/5 rounded">›</a>
        @else
            <span class="px-2 py-1 text-xs text-gray-600 bg-white/5 rounded">›</span>
        @endif
    </div>
@endif