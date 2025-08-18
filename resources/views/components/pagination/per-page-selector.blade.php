{{-- Per-page selector component --}}
<div class="flex flex-row justify-center md:justify-start mb-2 md:mb-0 py-1">
    <select id="{{ $uuid }}" @if(!empty($modelName())) wire:model.live="{{ $modelName() }}" @endif
            class="select select-sm flex sm:text-sm sm:leading-6 w-auto md:mr-5">
        @foreach ($perPageValues as $option)
        <option value="{{ $option }}" @selected($rows->perPage() === $option)>{{ $option }}</option>
        @endforeach
    </select>
</div>