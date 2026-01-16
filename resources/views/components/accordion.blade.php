<div
        x-data="{ model: @entangle($attributes->wire('model')) }"
        {{
            $attributes
                ->whereDoesntStartWith('wire:model')
                ->class([
                    $noJoin ? '' : 'join join-vertical w-full',
                    $glassClasses() => $glass,
                ])
        }}
        @if($glassStyle())
            style="{{ $glassStyle() }}"
        @endif
        wire:key="accordion-{{ $uuid }}"
>
    {{ $slot }}
</div>
