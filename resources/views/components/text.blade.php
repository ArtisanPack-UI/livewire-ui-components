<p 
    id="{{ $id }}"
    {{ $attributes->class([
        $sizeClass(),
        $fontWeightClass(),
        $colorClass(),
        'leading-relaxed',
        'text-center' => $center,
        'prose prose-base max-w-none' => $prose,
        'mb-4' => !$attributes->has('class') || !str_contains($attributes->get('class'), 'mb-'),
    ]) }}
>
    {{ $slot }}
</p>