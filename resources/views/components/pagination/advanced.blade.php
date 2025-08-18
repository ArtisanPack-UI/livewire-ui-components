{{-- Advanced variant: Data-heavy applications with jump-to-page and bulk navigation --}}
<div class="pagination-advanced space-y-4">
    {{-- Standard pagination links --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="pagination-links flex-1">
            {{ $rows->onEachSide($onEachSide)->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- Advanced controls --}}
    <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-base-content/10">
        {{-- Quick navigation buttons --}}
        <div class="flex items-center gap-2">
            <span class="text-sm text-base-content/70 mr-2">Quick jump:</span>
            
            {{-- First page --}}
            @if(!$rows->onFirstPage())
                <a href="{{ $rows->url(1) }}" class="btn btn-sm btn-outline" title="Go to first page">
                    First
                </a>
            @endif

            {{-- Jump back 10 pages --}}
            @if($rows->currentPage() > 10)
                <a href="{{ $rows->url(max(1, $rows->currentPage() - 10)) }}" class="btn btn-sm btn-outline" title="Go back 10 pages">
                    -10
                </a>
            @endif

            {{-- Jump forward 10 pages --}}
            @if($rows->currentPage() + 10 <= $rows->lastPage())
                <a href="{{ $rows->url(min($rows->lastPage(), $rows->currentPage() + 10)) }}" class="btn btn-sm btn-outline" title="Go forward 10 pages">
                    +10
                </a>
            @endif

            {{-- Last page --}}
            @if($rows->hasMorePages())
                <a href="{{ $rows->url($rows->lastPage()) }}" class="btn btn-sm btn-outline" title="Go to last page">
                    Last
                </a>
            @endif
        </div>

        {{-- Jump to page input --}}
        @if($showJumpTo)
        <div class="flex items-center gap-2">
            <label for="jump-to-page-{{ $uuid }}" class="text-sm text-base-content/70">Jump to page:</label>
            <input 
                type="number" 
                id="jump-to-page-{{ $uuid }}"
                min="1" 
                max="{{ $rows->lastPage() }}" 
                placeholder="{{ $rows->currentPage() }}"
                class="input input-sm w-20 text-center"
                onkeydown="if(event.key === 'Enter') { 
                    const page = parseInt(this.value);
                    if(page >= 1 && page <= {{ $rows->lastPage() }}) {
                        window.location.href = '{{ $rows->url('__PAGE__') }}'.replace('__PAGE__', page);
                    } else {
                        this.value = '';
                        this.placeholder = 'Invalid';
                        setTimeout(() => { this.placeholder = '{{ $rows->currentPage() }}'; }, 1500);
                    }
                }"
                title="Press Enter to jump to page"
            >
            <button 
                type="button"
                class="btn btn-sm btn-primary"
                onclick="
                    const input = document.getElementById('jump-to-page-{{ $uuid }}');
                    const page = parseInt(input.value);
                    if(page >= 1 && page <= {{ $rows->lastPage() }}) {
                        window.location.href = '{{ $rows->url('__PAGE__') }}'.replace('__PAGE__', page);
                    } else {
                        input.value = '';
                        input.placeholder = 'Invalid';
                        setTimeout(() => { input.placeholder = '{{ $rows->currentPage() }}'; }, 1500);
                    }
                "
                title="Jump to specified page"
            >
                Go
            </button>
        </div>
        @endif
    </div>
</div>