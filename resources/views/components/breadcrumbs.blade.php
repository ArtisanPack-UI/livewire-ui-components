<nav aria-label="Breadcrumb" wire:key="{{ $uuid }}">
<ul {{ $attributes->merge(['class' => 'flex items-center']) }}>
    @foreach($items as $element)

        {{-- Tooltip --}}
        <li
            @class(["lg:tooltip {$tooltipPosition($element)}" => $tooltip($element), "hidden sm:block" => !$loop->first && !$loop->last])

            @if($tooltip($element))
                data-tip="{{ $tooltip($element) }}"
            @endif
        >

            @if ($element['link'] ?? null)
                <a href="{{ $element['link'] }}" @if(!$noWireNavigate) wire:navigate @endif @class([$linkItemClass]) @if($loop->last) aria-current="page" @endif>
            @else
                <span @class([$textItemClass]) @if($loop->last) aria-current="page" @endif>
            @endif

                {{-- Icon --}}
                @if($element['icon'] ?? null)
                    <x-artisanpack-icon :name="$element['icon']" @class(["mb-0.5", $iconClass]) />
                @endif

                {{-- Text --}}
                <span>
                    {{ $element['label'] ?? null }}
                </span>

            @if ($element['link'] ?? null)
                </a>
            @else
                </span>
            @endif
        </li>

        @if($loop->remaining == 1 && $loop->count > 2)
            <span class="sm:hidden">...</span>
        @endif

        {{-- Separator --}}
        <span @class([
                "hidden",
                "!block" => ($loop->first || $loop->remaining == 1) && $loop->count > 1,
                "sm:!block" => !$loop->last && $loop->count > 1
             ])
        >
            <x-artisanpack-icon :name="$separator" @class([$separatorClass]) aria-hidden="true" />
        </span>
    @endforeach
</ul>
</nav>