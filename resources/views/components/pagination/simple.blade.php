{{-- Simple variant: Previous/Next buttons only with current page indicator --}}
<nav class="pagination-simple flex items-center justify-between w-full" role="navigation" aria-label="Pagination Navigation">
    <div class="flex items-center gap-2">
        {{-- Previous Button --}}
        @if($rows->onFirstPage())
            <span class="btn btn-disabled cursor-not-allowed opacity-50" aria-disabled="true">
                <span class="sr-only">Previous</span>
                &larr; Previous
            </span>
        @else
            <a href="{{ $rows->previousPageUrl() }}" class="btn btn-outline hover:btn-primary" 
               rel="prev" aria-label="Go to previous page">
                <span class="sr-only">Previous</span>
                &larr; Previous
            </a>
        @endif
        
        {{-- Current Page Indicator --}}
        <span class="text-sm text-base-content/70 px-3">
            Page {{ $rows->currentPage() }} of {{ $rows->lastPage() }}
        </span>
        
        {{-- Next Button --}}
        @if($rows->hasMorePages())
            <a href="{{ $rows->nextPageUrl() }}" class="btn btn-outline hover:btn-primary" 
               rel="next" aria-label="Go to next page">
                <span class="sr-only">Next</span>
                Next &rarr;
            </a>
        @else
            <span class="btn btn-disabled cursor-not-allowed opacity-50" aria-disabled="true">
                <span class="sr-only">Next</span>
                Next &rarr;
            </span>
        @endif
    </div>
</nav>