<div
    wire:key="{{ $uuid }}"
    {{ $attributes->whereDoesntStartWith('class') }}
    @php
        $colorClasses = $getColorClasses();
        $baseClasses = ['alert', 'rounded-md'];
        $inlineStyles = [];

        // Add shadow class if needed
        if ($shadow) {
            $baseClasses[] = 'shadow-md';
        }

        // Handle glass effect
        if ($glass) {
            $baseClasses[] = $glassClasses();
            if ($glassStyle()) {
                $inlineStyles[] = $glassStyle();
            }
        }

        // Handle new color system (only if not using glass)
        if (!empty($colorClasses) && !$glass) {
            // Add color classes from new system
            foreach ($colorClasses as $type => $class) {
                if ($type === 'style' && $class) {
                    // Handle inline styles for hex colors
                    $inlineStyles[] = $class;
                } elseif ($type !== 'style' && $class) {
                    $baseClasses[] = $class;
                }
            }
        }

        // Merge inline styles
        if (!empty($inlineStyles)) {
            $attributes = $attributes->merge(['style' => implode(' ', $inlineStyles)]);
        }
    @endphp
    {{ $attributes->class($baseClasses) }}
    x-data="{ show: true }" x-show="show"
>
    @if($icon)
        <x-artisanpack-icon :name="$icon" class="self-center" />
    @endif

    @if($title)
        <div>
            <div @class(["font-bold" => $description])>{{ $title }}</div>
            <div class="text-xs">{{ $description }}</div>
        </div>
    @else
        <span>{{ $slot }}</span>
    @endif

    <div class="flex items-center gap-3">
        {{ $actions }}
    </div>

    @if($dismissible)
        <x-artisanpack-button icon="o-x-mark" @click="show = false" class="btn-xs btn-circle btn-ghost static self-start end-0" />
    @endif
</div>