{{--
    ListItem (#58). Markup contract: design-system wave, ListItem.
    An li inside a list, a div on its own, with the leading, title, description
    and trailing parts. With href the title is a link and with interactive a
    button; either covers the row (data-interactive). selected sets
    data-selected and aria-current on that control. Named slots override the
    props. The deprecated 2.x item, value, sub-value and avatar attributes fill
    the row when the props are empty.
--}}
@php
    $rootTag      = $inList ? 'li' : 'div';
    $titleTag     = $titleTag();
    $href         = $linkHref();
    $isControl    = 'span' !== $titleTag;
    $legacyAvatar = $legacyValue( 'avatar', 'avatar' );
    $legacyTitle  = $legacyValue( 'value', 'name' );
    $legacyDesc   = $legacyValue( 'sub-value', null );
    $leadingSlot  = isset( $leading ) && $filled( $leading ) ? $leading : null;
    $trailingSlot = isset( $trailing ) && $trailing instanceof \Illuminate\View\ComponentSlot && $filled( $trailing ) ? $trailing : null;
    $titleSlot    = isset( $title ) && $title instanceof \Illuminate\View\ComponentSlot && $filled( $title ) ? $title : null;
    $descSlot     = isset( $description ) && $description instanceof \Illuminate\View\ComponentSlot && $filled( $description ) ? $description : null;
    $titleContent = $titleSlot ?? ( $filled( $title ) ? $title : ( $legacyTitle ?? ( isset( $slot ) && $filled( $slot ) ? $slot : '' ) ) );
    $descContent  = $descSlot ?? ( $filled( $description ) ? $description : $legacyDesc );
    $trailContent = $trailingSlot ?? ( $filled( $trailing ) ? $trailing : null );
    $hasLeading   = null !== $leadingSlot || $filled( $leadingIcon ) || null !== $legacyAvatar;
    $navigates    = 'a' === $titleTag && ! $disabled && $navigate && \ArtisanPack\LivewireUiComponents\Support\SafeUrl::navigable( $href );
    $attributes   = $attributes->except( \ArtisanPack\LivewireUiComponents\View\Components\ListItem::LEGACY_ATTRIBUTES );
@endphp
<{{ $rootTag }}
    {{ $attributes->class( $rootClasses( 'apui-list-item' ) ) }}
    @if ( $isControl ) data-interactive @endif
    @if ( $selected ) data-selected @endif
    @if ( $disabled ) data-disabled aria-disabled="true" @endif
>@if ( $hasLeading )<span data-scope="list-item" data-part="leading"{{ $partClass( 'leading' ) }}>@if ( null !== $leadingSlot ){{ $content( $leadingSlot ) }}@elseif ( $filled( $leadingIcon ) ){{ $iconSvg( $leadingIcon ) }}@else<x-artisanpack-avatar :src="$legacyAvatar" :name="$legacyTitle" />@endif</span>@endif<{{ $titleTag }}
        data-scope="list-item"
        data-part="title"{{ $partClass( 'title' ) }}
        @if ( 'a' === $titleTag )
            @if ( $disabled ) role="link" aria-disabled="true" @else href="{{ $href }}" @endif
            @if ( $navigates ) wire:navigate @endif
        @elseif ( 'button' === $titleTag )
            type="button"
            @if ( $disabled ) disabled @endif
        @endif
        @if ( $isControl && $selected ) aria-current="true" @endif
    >{{ $content( $titleContent ) }}</{{ $titleTag }}>@if ( $filled( $descContent ) )<span data-scope="list-item" data-part="description"{{ $partClass( 'description' ) }}>{{ $content( $descContent ) }}</span>@endif @if ( null !== $trailContent )<span data-scope="list-item" data-part="trailing"{{ $partClass( 'trailing' ) }}>{{ $content( $trailContent ) }}</span>@endif</{{ $rootTag }}>
