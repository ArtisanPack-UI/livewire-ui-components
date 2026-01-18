@php
    $baseClasses = [
        'kpi-card rounded-lg p-5 flex flex-col',
        'bg-base-100' => !$glass,
    ];
    $inlineStyles = [];

    // Handle glass effect
    if ($glass) {
        $baseClasses[] = $glassClasses();
        $computedGlassStyle = $glassStyle();
        if ($computedGlassStyle) {
            $inlineStyles[] = $computedGlassStyle;
        }
    }

    // Merge inline styles
    if (!empty($inlineStyles)) {
        $attributes = $attributes->merge(['style' => implode(' ', $inlineStyles)]);
    }

    // Merge wire:key and classes into attributes
    $attributes = $attributes->merge(['wire:key' => $uuid])->class($baseClasses);
@endphp

<div {{ $attributes }}>
    {{-- Header: Icon + Title --}}
    @if($icon || $title)
        <div class="flex items-center gap-2 mb-3">
            @if($icon)
                <div class="text-base-content/60">
                    <x-artisanpack-icon :name="$icon" class="w-5 h-5" />
                </div>
            @endif
            @if($title)
                <span class="text-sm text-base-content/60 font-medium">{{ $title }}</span>
            @endif
        </div>
    @endif

    {{-- Value --}}
    <div class="text-3xl font-bold">
        {{ $value ?? $slot }}
    </div>

    {{-- Trend Indicator --}}
    @if($hasChange())
        <div class="flex items-center gap-1 mt-2 {{ $changeColorClasses() }} text-sm">
            <x-artisanpack-icon :name="$changeIcon()" class="w-4 h-4" />
            <span class="font-medium">{{ $formattedChange() }}</span>
            @if($changeLabel)
                <span class="text-base-content/50">{{ $changeLabel }}</span>
            @endif
        </div>
    @endif

    {{-- Sparkline --}}
    @if($hasSparkline())
        <div class="mt-auto pt-4">
            <x-artisanpack-sparkline
                :data="$sparklineData"
                :type="$sparklineType"
                :color="$sparklineColor"
                :height="48"
            />
        </div>
    @endif

    {{-- Footer Slot --}}
    @if($footer)
        <div @if($footer instanceof \Illuminate\View\ComponentSlot && $footer->attributes){{ $footer->attributes->class(['border-t border-base-content/10 mt-4 pt-3']) }}@else class="border-t border-base-content/10 mt-4 pt-3"@endif>
            {{ $footer }}
        </div>
    @endif
</div>
