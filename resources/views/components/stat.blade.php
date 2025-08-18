@php
    $sizeClasses = $sizeClasses();
    $layoutClasses = $layoutClasses();
@endphp

<div
    {{ $attributes->class([
        "bg-base-100 rounded-lg w-full",
        $sizeClasses['container'],
        "lg:tooltip $tooltipPosition" => $tooltip
    ]) }}

    @if($tooltip)
        data-tip="{{ $tooltip }}"
    @endif
>
    <div class="{{ $layoutClasses['container'] }}">
        {{-- Icon First (left/top) --}}
        @if($icon && $shouldRenderIconFirst())
            <div class="{{ $color }}">
                <x-artisanpack-icon :name="$icon" :class="$sizeClasses['icon']" />
            </div>
        @endif

        <div class="{{ $layoutClasses['content'] }}">
            {{-- Title First (top position) --}}
            @if($title && $shouldRenderTitleFirst())
                <div class="text-base-content/50 whitespace-nowrap {{ $sizeClasses['title'] }}">
                    {{ $title }}
                </div>
            @endif

            {{-- Value/Slot --}}
            <div class="font-black {{ $sizeClasses['value'] }}">
                {{ $value ?? $slot }}
            </div>

            {{-- Title Last (bottom position) --}}
            @if($title && !$shouldRenderTitleFirst())
                <div class="text-base-content/50 whitespace-nowrap {{ $sizeClasses['title'] }}">
                    {{ $title }}
                </div>
            @endif

            {{-- Description --}}
            @if($description)
                <div class="stat-desc {{ $sizeClasses['description'] }}">
                    {{ $description }}
                </div>
            @endif
        </div>

        {{-- Icon Last (right/bottom) --}}
        @if($icon && !$shouldRenderIconFirst())
            <div class="{{ $color }}">
                <x-artisanpack-icon :name="$icon" :class="$sizeClasses['icon']" />
            </div>
        @endif
    </div>
</div>
