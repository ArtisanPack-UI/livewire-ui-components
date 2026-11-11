{{--
    MenuItem (#56). Markup contract: design-system Menu. An <a> with href
    (aria-current="page" when current), a <button> without one, or a disabled
    link with no href; a disallowed href renders a plain non-link. Every attribute lands on the item.
--}}
@unless ( $hidden )
    @php
        $text      = isset( $slot ) && $slot->hasActualContent() ? $content( $slot ) : $content( $labelText() ?? '' );
        $url       = $disabled ? null : $url();
        $unsafe    = ! $disabled && $hasUnsafeUrl();
        $isCurrent = $isCurrent();
        $bag       = $attributes->when( $filled( $classes['item'] ?? null ), fn ( $bag ) => $bag->class( $classes['item'] ) );
    @endphp
    <li>
        @if ( null !== $url )
            <a
                {{ $bag }}
                href="{{ $url }}"
                @if ( $external ) target="_blank" rel="noopener" @elseif ( $navigates() && ! str_starts_with( $url, '#' ) ) wire:navigate @endif
                data-scope="menu"
                data-part="item"
                @if ( $isCurrent ) aria-current="page" @endif
            >
        @elseif ( $disabled )
            <a {{ $bag }} role="link" data-scope="menu" data-part="item" aria-disabled="true">
        @elseif ( $unsafe )
            {{-- A disallowed href (javascript:, data:, …) renders as a plain non-link. --}}
            <a {{ $bag->except( [ 'href', 'wire:click', 'onclick' ] ) }} data-scope="menu" data-part="item">
        @else
            <button {{ $bag }} type="button" data-scope="menu" data-part="item" @if ( $isCurrent ) aria-current="page" @endif>
        @endif
            @if ( $filled( $icon ) )
                <span data-scope="menu" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}@if ( $mirrors( $icon ) ) data-mirror @endif>{{ $iconSvg( $icon ) }}</span>
            @endif
            <span data-scope="menu" data-part="item-text"{{ $partClass( 'itemText' ) }}>{{ $text }}</span>
            @if ( null !== $badge && '' !== (string) $badge )
                <span data-scope="menu" data-part="badge"{{ $partClass( 'badge' ) }}>{{ $badge }}</span>
            @endif
        @if ( null !== $url || $disabled || $unsafe )
            </a>
        @else
            </button>
        @endif
    </li>
@endunless
