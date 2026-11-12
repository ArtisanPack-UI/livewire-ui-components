{{--
    Avatar (#61). Markup contract: design-system wave, Avatar.
    A span, role=img named by alt or name plus the status in words; without a
    name it's decorative (aria-hidden). The image (alt="") sits over the
    initials, so a broken image shows them; without initials an icon shows.
    The deprecated 2.x image, placeholder and alt attributes still work.
--}}
@php
    $label      = $accessibleName();
    $src        = $imageSrc();
    $letters    = $initialsValue();
    $iconName   = $iconName();
    $status     = $statusValue();
    $attributes = $attributes->except( [ 'image', 'placeholder', 'alt', 'color-adjustment', 'colorAdjustment', 'subtitle' ] );
@endphp
<span
    {{ $attributes->class( $rootClasses( 'apui-avatar' ) ) }}
    data-size="{{ $sizeValue() }}"
    data-shape="{{ $shapeValue() }}"
    data-color="{{ $colorValue() }}"
    @if ( '' !== $label ) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif
>@if ( null !== $iconName ){{ $iconSvg( $iconName ) }}@else<span data-scope="avatar" data-part="initials" aria-hidden="true"{{ $partClass( 'initials' ) }}>{{ $letters }}</span>@endif @if ( null !== $src )<img data-scope="avatar" data-part="image" src="{{ $src }}" alt="" aria-hidden="true"{{ $partClass( 'image' ) }} />@endif @if ( null !== $status )<span data-scope="avatar" data-part="status" data-status="{{ $status }}" aria-hidden="true"{{ $partClass( 'status' ) }}></span>@endif</span>
