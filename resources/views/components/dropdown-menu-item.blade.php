{{--
    DropdownMenuItem (#50). Markup contract: design-system pilot, DropdownMenu.
    An item part: x-apui:item, or x-apui:option-item for checkbox and radio items,
    which report checked-change and whose checked state stays yours to set.
    The machine names items <menu id>/<value>, so the server renders that id,
    and the item carries wire:key (D-015). Every attribute lands on the item.
--}}
@php
    $labelHtml  = isset( $slot ) && $slot->hasActualContent() ? $content( $slot ) : $content( $label ?? '' );
    $itemValue  = $itemValue( $labelHtml->toHtml() );
    $args       = $partArgs( $itemValue );
    $itemId     = $itemId( $itemValue );
    $isOption   = $isOption();
    $hasIcon    = $filled( $icon );
@endphp
<div
    {{ $attributes->except( [ 'wire:key', 'id' ] )->when( $filled( $classes['item'] ?? null ), fn ( $bag ) => $bag->class( $classes['item'] ) ) }}
    wire:key="{{ $itemKey( $itemValue ) }}"
    @if ( null !== $itemId ) id="{{ $itemId }}" @endif
    data-scope="dropdown-menu"
    data-part="item"
    data-value="{{ $itemValue }}"
    @if ( $isOption )
        role="menuitem{{ $type }}"
        data-type="{{ $type }}"
        aria-checked="{{ $checked ? 'true' : 'false' }}"
        data-state="{{ $checked ? 'checked' : 'unchecked' }}"
        x-apui:option-item="{{ $args }}"
    @else
        role="menuitem"
        x-apui:item="{{ $args }}"
    @endif
    @if ( null !== $color && '' !== $color ) data-color="{{ $color }}" @endif
    @if ( $disabled ) aria-disabled="true" data-disabled @endif
>
    @if ( $isOption )
        <span data-scope="dropdown-menu" data-part="item-indicator" aria-hidden="true"{{ $partClass( 'itemIndicator' ) }} data-state="{{ $checked ? 'checked' : 'unchecked' }}" @if ( ! $checked ) hidden @endif x-apui:item-indicator="{{ $args }}">{{ $iconSvg( 'check' ) }}</span>
    @endif
    @if ( $hasIcon )
        <span data-scope="dropdown-menu" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}@if ( $mirrors( $icon ) ) data-mirror @endif>{{ $iconSvg( $icon ) }}</span>
    @endif
    <span data-scope="dropdown-menu" data-part="item-text"{{ $partClass( 'itemText' ) }} x-apui:item-text="{{ $args }}">{{ $labelHtml }}</span>
    @if ( $filled( $shortcut ) )
        <kbd data-scope="dropdown-menu" data-part="shortcut"{{ $partClass( 'shortcut' ) }}>{{ $shortcut }}</kbd>
    @endif
</div>
