@php
    $baseClasses = [
        'card rounded-lg',
        'bg-base-100' => !$glass,
        'shadow-xs' => $shadow,
        'p-5' => !$figure || in_array($figurePosition, ['top', 'bottom']),
        'flex' => $figure && in_array($figurePosition, ['left', 'right']),
        'flex-row' => $figure && $figurePosition === 'left',
        'flex-row-reverse' => $figure && $figurePosition === 'right',
    ];
    $inlineStyles = [];

    // Handle glass effect (call $glassStyle() only once)
    if ($glass) {
        $baseClasses[] = $glassClasses();
        $computedGlassStyle = $glassStyle();
        if ($computedGlassStyle) {
            $inlineStyles[] = $computedGlassStyle;
        }
    }

    // Merge inline styles (preserves user-provided styles)
    if (!empty($inlineStyles)) {
        $attributes = $attributes->merge(['style' => implode(' ', $inlineStyles)]);
    }

    // Merge wire:key and classes into attributes
    $attributes = $attributes->merge(['wire:key' => $uuid])->class($baseClasses);
@endphp
<div {{ $attributes }}>
    {{-- Figure at top --}}
    @if($figure && $figurePosition === 'top')
        <figure {{ $figure->attributes->class(["-m-5 mb-0 rounded-t-lg overflow-hidden"]) }}>
            {{ $figure }}
        </figure>
    @endif

    {{-- Figure at left --}}
    @if($figure && $figurePosition === 'left')
        <figure {{ $figure->attributes->class(["flex-shrink-0 rounded-l-lg overflow-hidden"]) }}>
            {{ $figure }}
        </figure>
    @endif

    {{-- Main content wrapper --}}
    <div @class([
        'flex flex-col',
        'p-5' => $figure && in_array($figurePosition, ['left', 'right']),
        'pt-5 px-5' => $figure && $figurePosition === 'top',
        'pb-5 px-5' => $figure && $figurePosition === 'bottom'
    ])>
        {{-- Header section --}}
        @if($title || $subtitle)
            <div class="pb-5">
                <div class="flex gap-3 justify-between items-center w-full">
                    <div class="grow-1">
                        @if($title)
                            <div @class(["text-xl font-bold", is_string($title) ? '' : $title?->attributes->get('class') ]) >
                                {{ $title }}
                            </div>
                        @endif
                        @if($subtitle)
                        <div @class(["text-base-content/50 text-sm mt-1", is_string($subtitle) ? '' : $subtitle?->attributes->get('class') ]) >
                                {{ $subtitle }}
                            </div>
                        @endif
                    </div>

                    @if($menu)
                        <div {{ $menu->attributes->class(["flex items-center gap-2"]) }}> {{ $menu }} </div>
                    @endif
                </div>

                @if($separator)
                    <hr class="mt-3 border-t-[length:var(--border)] border-base-content/10" />

                    @if($progressIndicator)
                        <div class="h-0.5 -mt-4 mb-4">
                            <progress
                                class="progress progress-primary w-full h-0.5 hidden data-loading:block"
                                wire:loading.class.remove="hidden"

                                @if($progressTarget())
                                    wire:target="{{ $progressTarget() }}"
                                 @endif></progress>
                        </div>
                    @endif
                @endif
            </div>
        @endif

        {{-- Main content --}}
        <div class="grow-1">
            {{ $slot }}
        </div>

        {{-- Actions section --}}
        @if($actions)
            @if($separator)
                <hr class="mt-5 border-t-[length:var(--border)] border-base-content/10" />
            @else
                <div></div>
            @endif

            <div @class(["flex w-full items-end justify-end gap-3 pt-5", is_string($actions) ? '' : $actions?->attributes->get('class') ])>
                {{ $actions }}
            </div>
        @endif
    </div>

    {{-- Figure at right --}}
    @if($figure && $figurePosition === 'right')
        <figure {{ $figure->attributes->class(["flex-shrink-0 rounded-r-lg overflow-hidden"]) }}>
            {{ $figure }}
        </figure>
    @endif

    {{-- Figure at bottom --}}
    @if($figure && $figurePosition === 'bottom')
        <figure {{ $figure->attributes->class(["-m-5 mt-0 rounded-b-lg overflow-hidden"]) }}>
            {{ $figure }}
        </figure>
    @endif
</div>