<div class="rating gap-1 {{ $size }}" x-cloak>
    <!-- NO RATING-->
    <input
        type="radio"
        name="{{ $modelName() }}"
        value="0"
        class="rating-hidden hidden"
        {{ $attributes->whereStartsWith('wire:model') }}
    />

    @for ($i = 1; $i <= $total; $i++)
        <input
            type="radio"
            name="{{ $modelName() }}"
            value="{{ $i }}"
            {{ $attributes->whereStartsWith('wire:model') }}
            {{ $attributes->class(["mask mask-star-2"]) }}
        />
    @endfor
</div>
