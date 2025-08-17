<div class="{{ $getVariantClasses() }}">
    <div {{ $attributes->class(["mb-4 border-t-[length:var(--border)] border-t-base-content/5"]) }}></div>
    
    {{-- Per-page selector (unless hidden or in simple/minimal mode) --}}
    @if($shouldShowPerPageSelector())
        @include('livewire-ui-components::components.pagination.per-page-selector')
    @endif

    {{-- Main pagination controls --}}
    <div class="pagination-controls {{ $size !== 'default' ? 'pagination-size-' . $size : '' }}">
        @if($simple)
            @include('livewire-ui-components::components.pagination.simple')
        @elseif($compact)
            @include('livewire-ui-components::components.pagination.compact')
        @elseif($advanced)
            @include('livewire-ui-components::components.pagination.advanced')
        @elseif($minimal)
            @include('livewire-ui-components::components.pagination.minimal')
        @else
            @include('livewire-ui-components::components.pagination.default')
        @endif
    </div>

    {{-- Page information --}}
    @if($shouldShowPageInfo())
        @include('livewire-ui-components::components.pagination.page-info')
    @endif
</div>
