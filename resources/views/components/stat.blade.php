<div
    {{ $attributes->class(["bg-base-100 rounded-lg px-5 py-4  w-full", "lg:tooltip $tooltipPosition" => $tooltip]) }}

    @if($tooltip)
        data-tip="{{ $tooltip }}"
    @endif
>
    <div class="flex items-center gap-3">
        @if($icon)
            <div class="  {{ $color }}">
                <x-artisanpack-icon :name="$icon" class="w-9 h-9" />
            </div>
        @endif

        <div class="text-left rtl:text-right">
            @if($title)
                <div class="text-xs text-base-content/50 whitespace-nowrap">{{ $title }}</div>
            @endif

            <div class="font-black text-xl">{{ $value ?? $slot }}</div>

            @if($description)
                <div class="stat-desc">{{ $description }}</div>
            @endif
        </div>
    </div>
</div>
