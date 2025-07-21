<li {{ $attributes->class(["menu-title"]) }}>
    <div class="flex items-center gap-2">

        @if($icon)
            <x-artisanpack-icon :name="$icon" @class([$iconClasses]) />
        @endif

        {{ $title }}
    </div>
</li>
