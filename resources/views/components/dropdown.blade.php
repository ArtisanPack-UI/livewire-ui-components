<details
    x-data="{
        open: false,
        focusedIndex: -1,
        items: [],
        init() {
            this.items = Array.from(this.$refs.menuItems.querySelectorAll('a, button'));
        },
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.focusedIndex = 0;
                this.$nextTick(() => { this.items[this.focusedIndex]?.focus(); });
            } else {
                this.focusedIndex = -1;
            }
        },
        handleKeydown(event) {
            if (!this.open) return;

            switch (event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    this.focusedIndex = (this.focusedIndex + 1) % this.items.length;
                    this.items[this.focusedIndex].focus();
                    break;
                case 'ArrowUp':
                    event.preventDefault();
                    this.focusedIndex = (this.focusedIndex - 1 + this.items.length) % this.items.length;
                    this.items[this.focusedIndex].focus();
                    break;
                case 'Escape':
                    this.open = false;
                    this.$refs.button.focus();
                    break;
            }
        }
    }"
    x-init="init()"
    @keydown="handleKeydown"
    @click.outside="open = false; focusedIndex = -1;"
    :open="open"
    @class([
        'dropdown',
        'dropdown-end' => ($noXAnchor && $right),
        'dropdown-top' => ($noXAnchor && $top),
        'dropdown-bottom' => $noXAnchor,
    ])
>
    @if($trigger)
        <summary x-ref="button" @click.prevent="toggle()" {{ $trigger->attributes->class(['list-none']) }}>
            {{ $trigger }}
        </summary>
    @else
        <summary x-ref="button" @click.prevent="toggle()" {{ $attributes->class(["btn"]) }}>
            {{ $label }}
            <x-artisanpack-icon :name="$icon" />
        </summary>
    @endif

    <ul
        x-ref="menuItems"
        @class([
            'p-2','shadow','menu','z-[1]','border-[length:var(--border)]','border-base-content/10','bg-base-100', 'rounded-box','w-auto','min-w-max',
            'dropdown-content' => $noXAnchor,
        ])
        @click="open = false; focusedIndex = -1;"
        @if(!$noXAnchor)
            x-anchor.{{ $right ? 'bottom-end' : 'bottom-start' }}="$refs.button"
        @endif
    >
        <div wire:key="dropdown-slot-{{ $uuid }}">
            {{ $slot }}
        </div>
    </ul>
</details>