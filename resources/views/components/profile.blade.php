<div
        x-data="{ open: false }"
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        @class([
            'dropdown',
            'dropdown-end' => $right,
            'dropdown-top' => $top,
            'relative'
        ])
>
    <button
            @click="open = !open"
            :aria-expanded="open"
            aria-haspopup="true"
            :aria-controls="$id('profile-dropdown-content')"
            {{ $attributes->class(['list-none', 'cursor-pointer', 'flex', 'items-center', 'gap-3']) }}
    >
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
                                // Fallback to default placeholder styling
                                $baseClasses[] = 'bg-neutral';
                                $baseClasses[] = 'text-neutral-content';
                            }
                        }
                    @endphp
                    class="{{ implode(' ', $baseClasses) }}"
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
                    <div @class(["font-semibold font-lg text-left", is_string($title) ? '' : $title?->attributes->get('class') ]) >
                        {{ $title }}
                    </div>
                @endif
                @if($subtitle)
                    <div @class(["text-sm text-base-content/50 text-left", is_string($subtitle) ? '' : $subtitle?->attributes->get('class') ]) >
                        {{ $subtitle }}
                    </div>
                @endif
            </div>
        @endif
    </button>

    <ul
            :id="$id('profile-dropdown-content')"
            x-show="open"
            x-trap.inert.noscroll="open"
            x-transition
            @class([
                'p-2','shadow','menu','z-[1]','border-[length:var(--border)]','border-base-content/10','bg-base-100', 'rounded-box','w-auto','min-w-max', 'absolute', 'right-0', 'mt-2'
            ])
            @click="open = false"
    >
        <div wire:key="profile-dropdown-slot-{{ $uuid }}">
            {{ $slot }}
        </div>
    </ul>
</div>
