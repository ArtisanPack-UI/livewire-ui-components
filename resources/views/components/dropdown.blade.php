<details
    x-data="{
        open: false,
        focusedIndex: -1,
        init() {
            this.$refs.menu.setAttribute('role', 'menu');
            this.$refs.button.setAttribute('aria-haspopup', 'true');
            this.$refs.button.setAttribute('aria-expanded', this.open.toString());
        },
        toggleDropdown(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.open = !this.open;
            this.$refs.button.setAttribute('aria-expanded', this.open.toString());
            if (this.open) {
                this.$nextTick(() => {
                    const firstItem = this.$refs.menu.querySelector('[role=menuitem]');
                    if (firstItem) {
                        firstItem.focus();
                        this.focusedIndex = 0;
                    }
                });
            } else {
                this.focusedIndex = -1;
            }
        },
        closeDropdown() {
            this.open = false;
            this.$refs.button.setAttribute('aria-expanded', 'false');
            this.$refs.button.focus();
            this.focusedIndex = -1;
        },
        navigateItems(direction) {
            const items = this.$refs.menu.querySelectorAll('[role=menuitem]');
            if (items.length === 0) return;

            if (direction === 'down') {
                this.focusedIndex = (this.focusedIndex + 1) % items.length;
            } else if (direction === 'up') {
                this.focusedIndex = this.focusedIndex <= 0 ? items.length - 1 : this.focusedIndex - 1;
            }
            items[this.focusedIndex].focus();
        }
    }"
    @click.outside="closeDropdown()"
    @keydown.escape="closeDropdown()"
    @click="toggleDropdown($event)"
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
        <summary
            x-ref="button"
            @click.prevent="toggleDropdown($event)"
            @keydown.enter.prevent="toggleDropdown()"
            @keydown.space.prevent="toggleDropdown()"
            @keydown.arrow-down.prevent="if (open) navigateItems('down')"
            @keydown.arrow-up.prevent="if (open) navigateItems('up')"
            role="button"
            tabindex="0"
            id="dropdown-button-{{ $uuid }}"
            {{ $trigger->attributes->class(['list-none']) }}
        >
            {{ $trigger }}
        </summary>
    @else
        <!-- DEFAULT TRIGGER -->
        <summary
            x-ref="button"
            @click.prevent="toggleDropdown($event)"
            @keydown.enter.prevent="toggleDropdown()"
            @keydown.space.prevent="toggleDropdown()"
            @keydown.arrow-down.prevent="if (open) navigateItems('down')"
            @keydown.arrow-up.prevent="if (open) navigateItems('up')"
            role="button"
            tabindex="0"
            id="dropdown-button-{{ $uuid }}"
            {{ $attributes->class(["btn"]) }}
        >
            {{ $label }}
            <x-artisanpack-icon :name="$icon" />
        </summary>
    @endif

    <ul
        x-ref="menu"
        @class([
            'p-2','shadow','menu','z-[1]','border-[length:var(--border)]','border-base-content/10','bg-base-100', 'rounded-box','w-auto','min-w-max',
            'dropdown-content' => $noXAnchor,
        ])
        @click="closeDropdown()"
        @keydown.arrow-down.prevent="navigateItems('down')"
        @keydown.arrow-up.prevent="navigateItems('up')"
        @keydown.escape.prevent="closeDropdown()"
        @keydown.tab="closeDropdown()"
        role="menu"
        aria-labelledby="dropdown-button-{{ $uuid }}"
        @if(!$noXAnchor)
            x-anchor.{{ $right ? 'bottom-end' : 'bottom-start' }}="$refs.button"
        @endif
    >
        <div wire:key="dropdown-slot-{{ $uuid }}">
            {{ $slot }}
        </div>
    </ul>
</details>
