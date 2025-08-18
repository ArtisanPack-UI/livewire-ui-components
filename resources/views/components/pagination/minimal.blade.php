{{-- Minimal variant: Clean, distraction-free interface with just page numbers --}}
<nav class="pagination-minimal flex items-center justify-center" role="navigation" aria-label="Pagination Navigation">
    <div class="flex items-center gap-1">
        {{-- Previous Page --}}
        @if($rows->onFirstPage())
            <span class="px-2 py-1 text-base-content/30 cursor-not-allowed" aria-disabled="true">
                &larr;
            </span>
        @else
            <a href="{{ $rows->previousPageUrl() }}" 
               class="px-2 py-1 text-base-content/70 hover:text-base-content transition-colors duration-150" 
               rel="prev" aria-label="Go to previous page">
                &larr;
            </a>
        @endif

        {{-- Page Numbers --}}
        @php
            $start = max(1, $rows->currentPage() - 2);
            $end = min($rows->lastPage(), $rows->currentPage() + 2);
        @endphp

        {{-- Show first page and ellipsis if needed --}}
        @if($start > 1)
            <a href="{{ $rows->url(1) }}" 
               class="px-3 py-1 text-base-content/70 hover:text-base-content transition-colors duration-150"
               aria-label="Go to page 1">
                1
            </a>
            @if($start > 2)
                <span class="px-2 py-1 text-base-content/30">...</span>
            @endif
        @endif

        {{-- Current page range --}}
        @for($page = $start; $page <= $end; $page++)
            @if($page == $rows->currentPage())
                <span class="px-3 py-1 font-medium text-base-content border-b-2 border-primary" 
                      aria-current="page" aria-label="Current page {{ $page }}">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $rows->url($page) }}" 
                   class="px-3 py-1 text-base-content/70 hover:text-base-content transition-colors duration-150"
                   aria-label="Go to page {{ $page }}">
                    {{ $page }}
                </a>
            @endif
        @endfor

        {{-- Show last page and ellipsis if needed --}}
        @if($end < $rows->lastPage())
            @if($end < $rows->lastPage() - 1)
                <span class="px-2 py-1 text-base-content/30">...</span>
            @endif
            <a href="{{ $rows->url($rows->lastPage()) }}" 
               class="px-3 py-1 text-base-content/70 hover:text-base-content transition-colors duration-150"
               aria-label="Go to page {{ $rows->lastPage() }}">
                {{ $rows->lastPage() }}
            </a>
        @endif

        {{-- Next Page --}}
        @if($rows->hasMorePages())
            <a href="{{ $rows->nextPageUrl() }}" 
               class="px-2 py-1 text-base-content/70 hover:text-base-content transition-colors duration-150" 
               rel="next" aria-label="Go to next page">
                &rarr;
            </a>
        @else
            <span class="px-2 py-1 text-base-content/30 cursor-not-allowed" aria-disabled="true">
                &rarr;
            </span>
        @endif
    </div>
</nav>