<ul
    {{
        $attributes->class([
            "menu w-full",
            $glassClasses() => $glass,
        ])
    }}
    @if($glassStyle())
        style="{{ $glassStyle() }}"
    @endif
>
    @if($title)
        <li class="menu-title text-inherit uppercase">
            <div class="flex items-center gap-2">

                @if($icon)
                    <x-artisanpack-icon :name="$icon" @class(['inline-flex', $iconClasses])  />
                @endif

                {{ $title }}
            </div>
        </li>
    @endif

    @if($separator)
        <hr class="mb-3 border-t-[length:var(--border)] border-base-content/10" />
    @endif

    {{ $slot }}
</ul>
