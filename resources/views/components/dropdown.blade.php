<div
        x-data="{ open: false }"
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        @class([
            'dropdown',
            'dropdown-end' => ($noXAnchor && $right),
            'dropdown-top' => ($noXAnchor && $top),
            'dropdown-bottom' => $noXAnchor,
            'relative' => !$noXAnchor
        ])
>
    <button
            @click="open = !open"
            :aria-expanded="open"
            aria-haspopup="true"
            :aria-controls="$id('dropdown-content')"
            {{ $trigger ? $trigger->attributes->class(['list-none']) : $attributes->class(["btn"]) }}
    >
        @if($trigger)
            {{ $trigger }}
        @else
            {{ $label }}
            <x-artisanpack-icon :name="$icon" />
        @endif
    </button>

    <ul
            :id="$id('dropdown-content')"
            x-show="open"
            x-trap.inert.noscroll="open"
            x-transition
            @class([
                'p-2','shadow','menu','z-[1]','border-[length:var(--border)]','border-base-content/10','bg-base-100', 'rounded-box','w-auto','min-w-max',
                'absolute' => !$noXAnchor,
                'dropdown-content' => $noXAnchor
            ])
            @click="open = false"
            @if(!$noXAnchor)
                x-anchor.{{ $right ? 'bottom-end' : 'bottom-start' }}="$el.parentElement.querySelector('button')"
            @endif
    >
        <div wire:key="dropdown-slot-{{ $uuid }}">
            {{ $slot }}
        </div>
    </ul>
</div>
