<div
    x-data="{ model: @entangle($attributes->wire('model')) }"
    {{ $attributes->whereDoesntStartWith('wire:model')->merge(['class' => ($noJoin ? '' : 'join join-vertical w-full')]) }}
    wire:key="accordion-{{ $uuid }}"
>
        {{ $slot }}
</div>