{{--
    Link (#56). Markup contract: design-system wave 4, Link.
    An anchor with a safe href, else a button styled the same. Named slots (icon,
    iconEnd) override the icon props. external opens a new tab, shows the
    external-link icon unless iconEnd is set, and says so in visually hidden text.
    Inside a Breadcrumbs it registers as a level of the trail instead.
--}}
@if ( null !== $trail )
@php
    $trail->addChild( isset( $slot ) ? $content( $slot ) : '', $linkHref(), $leadingIcon, false, $attributes );
@endphp
@else
@php
    // Deprecated 2.x opt-out: `no-wire-navigate` turns navigate off.
    $navigate   = $navigate && ! $attributes->has( 'no-wire-navigate' ) && ! $attributes->has( 'noWireNavigate' );
    $attributes = $attributes->except( [ 'no-wire-navigate', 'noWireNavigate' ] );
    $href        = $linkHref();
    $tag         = null !== $href ? 'a' : 'button';
    $iconSlot    = isset( $icon ) && $filled( $icon ) ? $icon : null;
    $iconEndSlot = isset( $iconEnd ) && $filled( $iconEnd ) ? $iconEnd : null;
    $endIcon     = $filled( $trailingIcon ) ? $trailingIcon : ( $external ? 'external-link' : null );
    $hasIcon     = null !== $iconSlot || $filled( $leadingIcon );
    $hasIconEnd  = null !== $iconEndSlot || null !== $endIcon;
@endphp
<{{ $tag }}
    @if ( 'a' === $tag )
        href="{{ $href }}"
        @if ( $external ) target="_blank" rel="noopener noreferrer" @endif
        @if ( $navigate && ! $external && \ArtisanPack\LivewireUiComponents\Support\SafeUrl::navigable( $href ) ) wire:navigate @endif
    @else
        type="button"
    @endif
    {{ $attributes->class( $rootClasses( 'apui-link' ) ) }}
    data-tone="{{ $toneValue() }}"
    @if ( $standalone ) data-standalone @endif
>@if ( $hasIcon )<span data-scope="link" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}@if ( null === $iconSlot && $mirrors( $leadingIcon ) ) data-mirror @endif>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $leadingIcon ) }}@endif</span>@endif{{ isset( $slot ) ? $content( $slot ) : '' }}@if ( $hasIconEnd )<span data-scope="link" data-part="iconEnd" aria-hidden="true"{{ $partClass( 'iconEnd' ) }}@if ( null === $iconEndSlot && $mirrors( $endIcon ) ) data-mirror @endif>@if ( null !== $iconEndSlot ){{ $content( $iconEndSlot ) }}@else{{ $iconSvg( $endIcon ) }}@endif</span>@endif@if ( $external )<span class="apui-sr-only"> {{ $externalLabel }}</span>@endif</{{ $tag }}>
@endif
