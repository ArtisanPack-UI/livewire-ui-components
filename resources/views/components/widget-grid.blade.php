<div
    {{ $attributes->merge(['wire:key' => $uuid])->class([
        'grid',
        $gridColsClasses(),
        $gapClass(),
    ]) }}
>
    {{ $slot }}
</div>
