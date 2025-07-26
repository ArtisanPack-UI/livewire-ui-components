<h{{ $level }} 
    id="{{ $id }}"
    {{ $attributes->class([
        $sizeClass(),
        $fontWeightClass(),
        $color ?? 'text-base-content',
        'tracking-tight',
        'text-center' => $center,
    ]) }}
>
    {{ $slot }}
</h{{ $level }}>