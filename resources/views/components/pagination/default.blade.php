{{-- Default variant: Enhanced version of current implementation --}}
<div class="justify-between md:flex md:flex-row w-auto md:w-full items-center overflow-y-auto pl-2 pr-2 relative">
    {{-- Main pagination links --}}
    <div class="w-full">
        @if($rows instanceof LengthAwarePaginator)
            {{ $rows->onEachSide($onEachSide)->links(data: ['scrollTo' => false]) }}
        @else
            {{ $rows->links(data: ['scrollTo' => false]) }}
        @endif
    </div>
</div>