@if($link)
    <a href="{!! $link !!}"
@else
    <button
@endif

    wire:key="{{ $uuid }}"
    {{ $attributes->whereDoesntStartWith('class')->merge(['type' => 'button']) }}
    @php
        $colorClasses = $getColorClasses();
        $baseClasses = ['btn', '!inline-flex', 'transition-all', 'duration-300'];
        $tooltipClass = $tooltip ? 'lg:tooltip ' . $tooltipPosition : '';
        
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
    @if($spinner && !$iconRight)
        <span wire:loading wire:target="{{ $spinnerTarget() }}" class="loading loading-spinner w-5 h-5"></span>
    @endif

    <!-- ICON -->
    @if($icon)
        <span class="block" @if($spinner) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
            <x-artisanpack-icon :name="$icon" />
        </span>
    @endif

    <!-- LABEL / SLOT -->
    @if($label)
        <span @class(["hidden lg:block" => $responsive ])>
            {{ $label }}
        </span>
        @if(strlen($badge ?? '') > 0)
            <span class="badge badge-sm {{ $badgeClasses }}">{{ $badge }}</span>
        @endif
    @else
        {{ $slot }}
    @endif

    <!-- ICON RIGHT -->
    @if($iconRight)
        <span class="block" @if($spinner) wire:loading.class="hidden" wire:target="{{ $spinnerTarget() }}" @endif>
            <x-artisanpack-icon :name="$iconRight" />
        </span>
    @endif

    <!-- SPINNER RIGHT -->
    @if($spinner && $iconRight)
        <span wire:loading wire:target="{{ $spinnerTarget() }}" class="loading loading-spinner w-5 h-5"></span>
    @endif

@if(!$link)
    </button>
@else
    </a>
@endif
