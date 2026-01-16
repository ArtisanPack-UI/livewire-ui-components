<div
    @php
        $colorClasses = $getColorClasses();
        $baseClasses = ['badge'];
        $inlineStyles = [];

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
>
    {{ $value }}
</div>