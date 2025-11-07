@props([
    'title' => null,
    'subtitle' => null,
    'separator' => false,
    'progressIndicator' => null,
    'progressIndicatorClass' => 'progress-primary',
    'withAnchor' => false,
    'size' => 'text-2xl',
    'level' => 2, // Default to <h2>
    'icon' => null,
    'iconClasses' => null,
    'middle' => null,
    'actions' => null,
    'anchor' => ''
])

<div id="{{ $anchor }}" {{ $attributes->class(["mb-10", "mary-header-anchor" => $withAnchor]) }}>
    <div class="flex flex-wrap gap-5 justify-between items-center">
        <div>
            {{-- This is the changed part: from <div> to <h{{ $level }}> --}}
            <h{{ $level }} @class([
                    "flex",
                    "items-center",
                    $size,
                    "font-extrabold",
                    is_string($title) ? '' : $title?->attributes->get('class')
                ])>

                @if($withAnchor)
                    <a href="#{{ $anchor }}" aria-label="Jump to {{ is_string($title) ? $title : '' }}">
                        @endif

                        @if($icon)
                            <x-artisanpack-icon name="{{ $icon }}" class="{{ $iconClasses }}" aria-hidden="true" />
                        @endif

                        <span @class(["ml-2" => $icon])>{{ $title }}</span>

                        @if($withAnchor)
                    </a>
                @endif
            </h{{ $level }}>
            {{-- End of changed part --}}

            @if($subtitle)
                <div @class(["text-base-content/50 text-sm mt-1", is_string($subtitle) ? '' : $subtitle?->attributes->get('class') ]) >
                    {{ $subtitle }}
                </div>
            @endif
        </div>

        @if($middle)
            <div @class(["flex items-center justify-center gap-3 grow order-last sm:order-none", is_string($middle) ? '' : $middle?->attributes->get('class')])>
                <div class="w-full lg:w-auto">
                    {{ $middle }}
                </div>
            </div>
        @endif

        <div @class(["flex items-center gap-3", is_string($actions) ? '' : $actions?->attributes->get('class') ]) >
            {{ $actions}}
        </div>
    </div>

    @if($separator)
        <hr class="border-t-[length:var(--border)] border-base-content/10 mt-3" />

        @if($progressIndicator)
            <div class="h-0.5 -mt-4 mb-4" role="status" aria-live="polite" aria-label="Loading progress">
                <progress
                    class="progress {{ $progressIndicatorClass }} w-full h-[var(--border)]"
                    wire:loading
                    aria-label="Loading"
                    @if($progressTarget())
                        wire:target="{{ $progressTarget() }}"
                    @endif></progress>
            </div>
        @endif
    @endif
</div>