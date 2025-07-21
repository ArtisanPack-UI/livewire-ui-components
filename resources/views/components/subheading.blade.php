<p 
    id="{{ $id }}"
    {{ $attributes->class([
        $size ?? 'text-lg',
        $fontWeightClass(),
        $colorClass(),
        'leading-relaxed',
        'text-center' => $center,
    ]) }}
>
    {{ $slot }}
</p>