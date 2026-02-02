<div
        x-data="{ model: $wire.entangle('{{ $attributes->wire('model')->value() }}') }"
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
