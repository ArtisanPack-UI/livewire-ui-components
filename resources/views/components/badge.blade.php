<div
    role="status"
    @php
        $colorClasses = $getColorClasses();
        $baseClasses = ['badge'];

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
        }
    @endphp
    {{ $attributes->class($baseClasses) }}
>
    {{ $value }}
</div>