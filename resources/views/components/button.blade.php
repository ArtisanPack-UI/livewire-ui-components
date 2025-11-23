{{--
    Tailwind JIT Safelist - DO NOT REMOVE
    These classes are generated dynamically but need to be visible for Tailwind to generate them:
    hover:bg-[var(--artisanpack-variant-hover-color)] hover:text-[var(--artisanpack-variant-hover-text)]
    focus:bg-[var(--artisanpack-variant-focus-color)] focus:text-[var(--artisanpack-variant-focus-text)]
    hover:bg-[var(--artisanpack-tailwind-hover-color)] hover:text-[var(--artisanpack-tailwind-hover-text)]
    focus:bg-[var(--artisanpack-tailwind-focus-color)] focus:text-[var(--artisanpack-tailwind-focus-text)]
    hover:bg-[var(--artisanpack-custom-hover-color)] hover:text-[var(--artisanpack-custom-hover-text)]
    focus:bg-[var(--artisanpack-custom-focus-color)] focus:text-[var(--artisanpack-custom-focus-text)]
    bg-[var(--artisanpack-tailwind-color)] border-[var(--artisanpack-tailwind-color)]
    bg-[var(--artisanpack-custom-color)] border-[var(--artisanpack-custom-color)]
--}}
@if($link)
    <a href="{!! $link !!}"
@else
    <button
@endif

    wire:key="{{ $uuid }}"
    {{ $attributes->whereDoesntStartWith('class')->merge(['type' => 'button']) }}
    @php
        $colorClasses = $getColorClasses();
        $baseClasses = ['btn', '!inline-flex', 'transition-colors', 'duration-200', 'ease-in-out'];
        $tooltipClass = $tooltip ? 'lg:tooltip ' . $tooltipPosition : '';

        // Add size classes
        $baseClasses[] = $getSizeClasses();

        // Handle new color system
        if (!empty($colorClasses)) {
            // Add color classes from new system
            foreach ($colorClasses as $type => $class) {
                if ($type === 'style' && $class) {
                    // Handle inline styles for hex colors
                    $attributes = $attributes->merge(['style' => $class]);
                } elseif ($type !== 'style' && $class) {
                    $baseClasses[] = $class;
                }
            }
        } else {
            // Fall back to legacy variant classes
            $baseClasses[] = $getVariantClasses();
        }

        if ($tooltipClass) {
            $baseClasses[] = $tooltipClass;
        }

        // DEBUG: Uncomment to see what classes are being added
        // dd($baseClasses, $colorClasses, $attributes->get('class'));
    @endphp
    {{ $attributes->class($baseClasses) }}

    @if($link && $external)
        target="_blank"
    @endif

    @if($link && !$external && !$noWireNavigate)
        wire:navigate
    @endif

    @if($tooltip)
        data-tip="{{ $tooltip }}"
    @endif

    @if($spinner)
        wire:target="{{ $spinnerTarget() }}"
        wire:loading.attr="disabled"
    @endif
>

    <!-- SPINNER LEFT -->
    @if($spinner && !$iconRight && !$loading)
        <span wire:loading wire:target="{{ $spinnerTarget() }}" class="loading loading-spinner w-5 h-5"></span>
    @endif

    <!-- LOADING CONTENT (icon or text, shows during loading) -->
    @if($loading)
        @if($isLoadingIcon())
            <span wire:loading wire:target="{{ $spinnerTarget() }}" class="block">
                <x-artisanpack-icon :name="$loading" />
            </span>
        @else
            <span wire:loading wire:target="{{ $spinnerTarget() }}" @class(["hidden lg:block" => $responsive ])>
                {{ $loading }}
            </span>
        @endif
    @endif

    <!-- ICON -->
    @if($icon)
        <span class="block" @if($spinner) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
            <x-artisanpack-icon :name="$icon" />
        </span>
    @endif

    <!-- LABEL / SLOT -->
    @if($label)
        <span @class(["hidden lg:block" => $responsive ]) @if($spinner && $loading) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
            {{ $label }}
        </span>
        @if(strlen($badge ?? '') > 0)
            <span class="badge badge-sm {{ $badgeClasses }}">{{ $badge }}</span>
        @endif
    @else
        <span @if($spinner && $loading) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
            {{ $slot }}
        </span>
    @endif

    <!-- ICON RIGHT -->
    @if($iconRight)
        <span class="block" @if($spinner) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
            <x-artisanpack-icon :name="$iconRight" />
        </span>
    @endif

    <!-- SPINNER RIGHT -->
    @if($spinner && $iconRight && !$loading)
        <span wire:loading wire:target="{{ $spinnerTarget() }}" class="loading loading-spinner w-5 h-5"></span>
    @endif

@if(!$link)
    </button>
@else
    </a>
@endif
