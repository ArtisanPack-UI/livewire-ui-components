{{-- Compact variant: Mobile-optimized with condensed buttons and icons --}}
<nav class="pagination-compact flex items-center justify-center gap-1" role="navigation" aria-label="Pagination Navigation">
    {{-- First Page Button --}}
    @if($showFirstLast && !$rows->onFirstPage())
        <a href="{{ $rows->url(1) }}" class="btn btn-sm btn-ghost" aria-label="Go to first page" title="First">
            <x-artisanpack-icon name="c-chevron-double-left" class="w-4 h-4" />
        </a>
    @endif

    {{-- Previous Page Button --}}
    @if($rows->onFirstPage())
        <span class="btn btn-sm btn-disabled" aria-disabled="true" title="Previous">
            <x-artisanpack-icon name="c-chevron-left" class="w-4 h-4" />
        </span>
    @else
        <a href="{{ $rows->previousPageUrl() }}" class="btn btn-sm btn-ghost" rel="prev" aria-label="Go to previous page" title="Previous">
            <x-artisanpack-icon name="c-chevron-left" class="w-4 h-4" />
        </a>
    @endif

    {{-- Page Numbers (limited for compact view) --}}
    @php
        $start = max(1, $rows->currentPage() - 1);
        $end = min($rows->lastPage(), $rows->currentPage() + 1);
    @endphp

    @for($page = $start; $page <= $end; $page++)
        @if($page == $rows->currentPage())
            <span class="btn btn-sm btn-primary" aria-current="page" aria-label="Current page {{ $page }}">
                {{ $page }}
            </span>
        @else
            <a href="{{ $rows->url($page) }}" class="btn btn-sm btn-ghost" aria-label="Go to page {{ $page }}">
                {{ $page }}
            </a>
        @endif
    @endfor

    {{-- Next Page Button --}}
    @if($rows->hasMorePages())
        <a href="{{ $rows->nextPageUrl() }}" class="btn btn-sm btn-ghost" rel="next" aria-label="Go to next page" title="Next">
            <x-artisanpack-icon name="c-chevron-right" class="w-4 h-4" />
        </a>
    @else
        <span class="btn btn-sm btn-disabled" aria-disabled="true" title="Next">
            <x-artisanpack-icon name="c-chevron-right" class="w-4 h-4" />
        </span>
    @endif

    {{-- Last Page Button --}}
    @if($showFirstLast && $rows->hasMorePages())
        <a href="{{ $rows->url($rows->lastPage()) }}" class="btn btn-sm btn-ghost" aria-label="Go to last page" title="Last">
            <x-artisanpack-icon name="c-chevron-double-right" class="w-4 h-4" />
        </a>
    @endif
</nav>

{{-- Compact page info --}}
<div class="text-xs text-center text-base-content/60 mt-2">
    {{ $rows->firstItem() }}-{{ $rows->lastItem() }} of {{ $rows->total() }}
</div>