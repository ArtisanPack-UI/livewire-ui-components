<div class="mary-table-pagination">
    <div {{ $attributes->class(["mb-4 border-t-[length:var(--border)] border-t-base-content/5"]) }}></div>
    <div class="justify-between md:flex md:flex-row w-auto md:w-full items-center overflow-y-auto pl-2 pr-2 relative">
        @if($isShowable())
        <div class="flex flex-row justify-center md:justify-start mb-2 md:mb-0 py-1">
            <select id="{{ $uuid }}" @if(!empty($modelName())) wire:model.live="{{ $modelName() }}" @endif
                    class="select select-sm flex sm:text-sm sm:leading-6 w-auto md:mr-5">
                @foreach ($perPageValues as $option)
                <option value="{{ $option }}" @selected($rows->perPage() === $option)>{{ $option }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="w-full">
        @if($rows instanceof LengthAwarePaginator)
            {{ $rows->onEachSide(1)->links(data: ['scrollTo' => false]) }}
        @else
            {{ $rows->links(data: ['scrollTo' => false]) }}
        @endif
        </div>
    </div>
</div>
