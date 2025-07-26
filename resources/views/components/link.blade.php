<a 
    id="{{ $id }}"
    href="{{ $href }}"
    {{ $attributes->class([
        'inline-flex items-center gap-1',
        $colorClass(),
        $underlineClass(),
        "lg:tooltip $tooltipPosition" => $tooltip,
    ]) }}
    
    @if($external)
        target="_blank"
        rel="noopener noreferrer"
    @endif

    @if(!$external && !$noWireNavigate)
        wire:navigate
    @endif

    @if($tooltip)
        data-tip="{{ $tooltip }}"
    @endif
>
    @if($icon)
        <x-artisanpack-icon :name="$icon" />
    @endif

    {{ $slot }}

    @if($iconRight)
        <x-artisanpack-icon :name="$iconRight" />
    @endif
</a>