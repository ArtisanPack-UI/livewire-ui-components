<details
    x-data="{open: false}"
    @click.outside="open = false"
    :open="open"
    @class([
        'dropdown',
        'dropdown-end' => ($noXAnchor && $right),
        'dropdown-top' => ($noXAnchor && $top),
        'dropdown-bottom' => $noXAnchor,
    ])
>
    <!-- CUSTOM TRIGGER -->
    @if($trigger)
        <summary x-ref="button" @click.prevent="open = !open" {{ $trigger->attributes->class(['list-none']) }}>
            {{ $trigger }}
        </summary>
    @else
        <!-- DEFAULT TRIGGER -->
        <summary x-ref="button" @click.prevent="open = !open" {{ $attributes->class(["btn"]) }}>
            {{ $label }}
            <x-artisanpack-icon :name="$icon" />
        </summary>
    @endif

    <ul
        @class([
            'p-2','shadow','menu','z-[1]','border-[length:var(--border)]','border-base-content/10','bg-base-100', 'rounded-box','w-auto','min-w-max',
            'dropdown-content' => $noXAnchor,
        ])
        @click="open = false"
        @if(!$noXAnchor)
            x-anchor.{{ $right ? 'bottom-end' : 'bottom-start' }}="$refs.button"
        @endif
    >
        <div wire:key="dropdown-slot-{{ $uuid }}">
            {{ $slot }}
        </div>
    </ul>
</details>
