@aware(['activateByRoute' => false, 'activeBgColor' => 'bg-base-300'])

@php
    // FIX: Only perform automatic route matching if the `:active` prop was NOT passed.
    $isActive = $active || ($activateByRoute && $attributes->get('active') === null && $routeMatches());

    $classes = ['flex', 'items-center', 'gap-3', 'my-0.5', 'p-2'];
    $extraAttributes = [];

    if ($isActive) {
        $classes[] = 'artisanpack-active-menu';
    }

    if ($dynamicBgColor) {
        $classes[] = 'has-dynamic-color';
        $extraAttributes['style'] = '--dynamic-bg-color: ' . $dynamicBgColor . '; --dynamic-text-color: ' . $dynamicTextColor . ';';
    } else {
        if ($bgColor) {
            $classes = array_merge($classes, data_get($themeColorClasses, 'hover-focus', []));
            if ($isActive) {
                $classes[] = $themeColorClasses['active-bg'] ?? null;
                $classes[] = $themeColorClasses['active-text'] ?? null;
            }
        } else {
            $classes[] = 'hover:text-inherit';
            if ($isActive) {
                $classes[] = $activeBgColor;
            }
        }
    }
@endphp

<li @class(['menu-disabled' => $disabled])>
    <a
        {{ $attributes->class($classes)->merge($extraAttributes) }}

        @if($link)
            href="{{ $link }}"
        @endif

        @if($external)
            target="_blank"
        @endif

        @if(!$external && !$noWireNavigate)
            wire:navigate
        @endif

        @if($spinner)
            wire:target="{{ $spinnerTarget() }}"
        wire:loading.attr="disabled"
        @endif
    >
        {{-- ICON --}}
        <div class="w-5">
            @if($spinner)
                <span wire:loading wire:target="{{ $spinnerTarget() }}" class="loading loading-spinner w-5 h-5"></span>
            @endif

            @if($icon)
                <span class="block py-0.5" @if($spinner) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
                    <x-artisanpack-icon :name="$icon" @class(['mb-0.5', $iconClasses]) />
                </span>
            @endif
        </div>

        @if($title || $slot->isNotEmpty())
            <span class="artisanpack-hideable whitespace-nowrap truncate flex-1">
            @if($title)
                    {{ $title }}

                    @if($badge)
                        <span class="badge badge-sm {{ $badgeClasses }}">{{ $badge }}</span>
                    @endif
                @else
                    {{ $slot }}
                @endif
        </span>
        @endif
    </a>
</li>
