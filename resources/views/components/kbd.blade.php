<kbd
    wire:key="{{ $uuid }}"
    {{ $attributes->merge(["class" => "kbd"]) }}
>
    {{ $slot }}
</kbd>
