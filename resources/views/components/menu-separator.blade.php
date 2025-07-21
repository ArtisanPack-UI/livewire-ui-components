<hr class="my-3 border-t-[length:var(--border)] border-base-content/10"/>

@if($title)
    <li {{ $attributes->class(["menu-title text-inherit uppercase"]) }}>
        <div class="flex items-center gap-2">

            @if($icon)
                <x-artisanpack-icon :name="$icon" @class([$iconClasses]) />
            @endif

            {{ $title }}
        </div>
    </li>
@endif
