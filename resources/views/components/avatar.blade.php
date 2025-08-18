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
            {{ $attributes->class($baseClasses) }}
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