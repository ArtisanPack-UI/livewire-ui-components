{{--
    Stat (#61). Markup contract: design-system wave 5, Stat.
    The top row (label, icon tile), the value (with its unit) and the foot
    (delta, description). The delta carries hidden direction text and an
    arrow, so color is never the only signal. The default slot replaces
    value; the icon slot replaces the named icon. 2.x title and change-label
    still work, as label and description.
--}}
@php
    $label       = $filled( $label ) ? $label : $attributes->get( 'title' );
    $description = $filled( $description ) ? $description : ( $attributes->get( 'change-label' ) ?? $attributes->get( 'changeLabel' ) );
    $attributes  = $attributes->except( \ArtisanPack\LivewireUiComponents\View\Components\Stat::DEPRECATED_ATTRIBUTES );
    $trend       = $trendValue();
    $deltaText   = $deltaText();
    $trendIcon   = \ArtisanPack\LivewireUiComponents\View\Components\Stat::trendIcon( $trend );
    $trendLabel  = $trendLabel();
    $hasIconSlot = isset( $icon ) && $icon instanceof \Illuminate\View\ComponentSlot && $icon->hasActualContent();
    $hasIcon     = $hasIconSlot || $filled( $icon );
    $valueHtml   = isset( $slot ) && $slot->hasActualContent() ? $content( $slot ) : $content( $value ?? '' );
@endphp
<div
    {{ $attributes->class( $rootClasses( 'apui-stat' ) ) }}
    data-size="{{ $sizeValue() }}"
    data-color="{{ $colorValue() }}"
    @if ( $emphasis ) data-emphasis @endif
    @if ( $bare ) data-bare @endif
>
    @if ( $filled( $label ) || $hasIcon )
        <div data-scope="stat" data-part="top"{{ $partClass( 'top' ) }}>@if ( $filled( $label ) )<span data-scope="stat" data-part="label"{{ $partClass( 'label' ) }}>{{ $label }}</span>@endif @if ( $hasIcon )<span data-scope="stat" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>@if ( $hasIconSlot ){{ $icon }}@else{{ $iconSvg( $icon ) }}@endif</span>@endif</div>
    @endif
    <span data-scope="stat" data-part="value"{{ $partClass( 'value' ) }}>{{ $valueHtml }}@if ( $filled( $unit ) )<span data-scope="stat" data-part="unit"{{ $partClass( 'unit' ) }}>{{ $unit }}</span>@endif</span>
    @if ( null !== $deltaText || $filled( $description ) )
        <span data-scope="stat" data-part="foot"{{ $partClass( 'foot' ) }}>@if ( null !== $deltaText )<span data-scope="stat" data-part="delta"{{ $partClass( 'delta' ) }}@if ( null !== $trend ) data-trend="{{ $trend }}"@endif>@if ( null !== $trendLabel )<span class="apui-sr-only">{{ $trendLabel }} </span>@endif{{-- --}}@if ( null !== $trendIcon ){{ $iconSvg( $trendIcon ) }}@endif{{ $deltaText }}</span>@endif @if ( $filled( $description ) )<span data-scope="stat" data-part="description"{{ $partClass( 'description' ) }}>{{ $description }}</span>@endif</span>
    @endif
</div>
