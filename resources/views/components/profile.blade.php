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
    <!-- AVATAR TRIGGER -->
    <summary
        x-ref="button"
        @click.prevent="toggleDropdown($event)"
        @keydown.enter.prevent="toggleDropdown()"
        @keydown.space.prevent="toggleDropdown()"
        @keydown.arrow-down.prevent="if (open) navigateItems('down')"
        @keydown.arrow-up.prevent="if (open) navigateItems('up')"
        role="button"
        tabindex="0"
        id="profile-button-{{ $uuid }}"
        {{ $attributes->class(['list-none', 'cursor-pointer']) }}
    >
        <div class="flex items-center gap-3">
            <div class="avatar @if(empty($image)) avatar-placeholder @endif">
                <div
                    @php
                        $colorClasses = $getColorClasses();
                        $baseClasses = ["w-7", "rounded-full"];

                        // Handle placeholder styling
                        if (empty($image)) {
                            if (!empty($colorClasses)) {
                                // Use new color system for placeholder
                                foreach ($colorClasses as $type => $class) {
                                    if ($type === 'style' && $class) {
                                        // Handle inline styles for hex colors
                                        $attributes = $attributes->merge(['style' => $class]);
                                    } elseif ($type !== 'style' && $class) {
                                        $baseClasses[] = $class;
                                    }
                                }
                            } else {
                                // Fall back to default neutral styling
                                $baseClasses[] = "bg-neutral";
                                $baseClasses[] = "text-neutral-content";
                            }
                        }
                    @endphp
                    class="{{ implode(' ', $baseClasses) }}"
                >
                    @if(empty($image))
                        <span class="text-xs" alt="{{ $alt }}">{{ $placeholder }}</span>
                    @else
                        <img src="{{ $image }}" alt="{{ $alt }}" />
                    @endif
                </div>
            </div>
            @if($title || $subtitle)
                <div>
                    @if($title)
                        <div @class(["font-semibold font-lg", is_string($title) ? '' : $title?->attributes->get('class') ]) >
                            {{ $title }}
                        </div>
                    @endif
                    @if($subtitle)
                        <div @class(["text-sm text-base-content/50", is_string($subtitle) ? '' : $subtitle?->attributes->get('class') ]) >
                            {{ $subtitle }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </summary>

    <!-- DROPDOWN CONTENT -->
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
        aria-labelledby="profile-button-{{ $uuid }}"
        @if(!$noXAnchor)
            x-anchor.{{ $right ? 'bottom-end' : 'bottom-start' }}="$refs.button"
        @endif
    >
        <div wire:key="profile-dropdown-slot-{{ $uuid }}">
            {{ $slot }}
        </div>
    </ul>
</details>