@aware(['activeBgColor' => 'bg-base-300'])

@php
    $submenuActive = $active;
    $classes = ['flex', 'items-center', 'gap-3', 'my-0.5', 'py-1.5', 'px-4'];
    $extraAttributes = [];
    if ($submenuActive) { $classes[] = 'artisanpack-active-menu'; }
    if ($dynamicBgColor) {
        $classes[] = 'has-dynamic-color';
        $extraAttributes['style'] = '--dynamic-bg-color: ' . $dynamicBgColor . '; --dynamic-text-color: ' . $dynamicTextColor . ';';
    } else {
        if ($bgColor) {
            $classes = array_merge($classes, data_get($themeColorClasses, 'hover-focus', []));
            if ($submenuActive) {
                $classes[] = $themeColorClasses['active-bg'] ?? null;
                $classes[] = $themeColorClasses['active-text'] ?? null;
            }
        } else {
            $classes[] = 'hover:text-inherit';
            if ($submenuActive) {
                $classes[] = $activeBgColor;
            }
        }
    }
@endphp

@if ($slot->isNotEmpty())
    <li
        @class(['menu-disabled' => $disabled])
        x-data="{
            show: @if($open) true @else false @endif,
            items: [],
            focusedIndex: -1,
            init() {
                this.items = Array.from(this.$el.querySelectorAll('[role=\'menuitem\']'));
            },
            toggle(event) {
                event.preventDefault();
                event.stopPropagation();

                if (this.collapsed) {
                    this.show = true;
                    $dispatch('menu-sub-clicked');
                    return;
                }

                this.show = !this.show;
                if (this.show) {
                    this.focusedIndex = 0;
                    this.$nextTick(() => { this.items[this.focusedIndex]?.focus(); });
                }
            },
            close() {
                if (!this.show) return;
                this.show = false;
                this.focusedIndex = -1;
                this.$el.querySelector('summary').focus();
            },
            handleKeydown(event) {
                if (!this.show) return;

                switch (event.key) {
                    case 'ArrowDown':
                    case 'ArrowUp':
                        event.preventDefault();
                        if (event.key === 'ArrowDown') {
                            this.focusedIndex = (this.focusedIndex + 1) % this.items.length;
                        } else {
                            this.focusedIndex = (this.focusedIndex - 1 + this.items.length) % this.items.length;
                        }
                        this.items[this.focusedIndex].focus();
                        break;
                    case 'Escape':
                        event.preventDefault();
                        if (this.$el.contains(document.activeElement)) {
                            this.close();
                        }
                        break;
                }
            }
        }"
        x-init="init()"
        @keydown="handleKeydown"
    >
        <details :open="show" @if($open) open @endif>
            <summary
                @click="toggle($event)"
                {{ $attributes->class($classes)->merge($extraAttributes) }}
            >
                <div class="w-5">
                    @if($icon)
                        <span class="block py-0.5">
                        <x-artisanpack-icon :name="$icon" @class(['mb-0.5', $iconClasses]) />
                    </span>
                    @endif
                </div>
                <span class="artisanpack-hideable whitespace-nowrap truncate flex-1">{{ $title }}</span>
            </summary>

            <ul class="artisanpack-hideable" role="menu">
                {{ $slot }}
            </ul>
        </details>
    </li>
@endif