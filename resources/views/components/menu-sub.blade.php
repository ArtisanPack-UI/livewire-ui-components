@aware(['activeBgColor' => 'bg-base-300'])

@php
    // Use the explicit `$active` property passed from the helper. This is reliable.
    $submenuActive = $active;

    $classes = ['flex', 'items-center', 'gap-3', 'my-0.5', 'py-1.5', 'px-4'];
    $extraAttributes = [];

    if ($submenuActive) {
        $classes[] = 'artisanpack-active-menu';
    }

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
        x-data="
    {
        show: @if($open) true @else false @endif,
        toggle(){
            // From parent Sidebar
            if (this.collapsed) {
                this.show = true
                $dispatch('menu-sub-clicked');
                return
            }

            this.show = !this.show
        }
    }"
    >
        <details :open="show" @if($open) open @endif @click.stop>
            <summary
                @click.prevent="toggle()"
                {{ $attributes->class($classes)->merge($extraAttributes) }}
            >
                {{-- ICON --}}
                <div class="w-5">
                    @if($icon)
                        <span class="block py-0.5">
                        <x-artisanpack-icon :name="$icon" @class(['mb-0.5', $iconClasses]) />
                    </span>
                    @endif
                </div>

                <span class="artisanpack-hideable whitespace-nowrap truncate flex-1">{{ $title }}</span>
            </summary>

            <ul class="artisanpack-hideable">
                {{ $slot }}
            </ul>
        </details>
    </li>
@endif
