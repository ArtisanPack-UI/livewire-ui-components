{{--
    Divider (#58). Markup contract: design-system Divider. An <hr> for a plain
    horizontal rule; a role="separator" div for a vertical or labelled one,
    named by its label. Decorative dividers are hidden from assistive
    technology (a labelled one becomes role="none", so its text still reads).
--}}
@php
    $labelContent = $filled( $label ) ? $label : ( isset( $slot ) && $slot->hasActualContent() ? $slot : null );
    $hasLabel     = null !== $labelContent;
    $rootAttributes = $attributes->class( $rootClasses( 'apui-divider' ) );
    // The deprecated `separator` alias took `id` as a prop.
    if ( isset( $id ) && is_string( $id ) && '' !== $id ) {
        $rootAttributes = $rootAttributes->merge( [ 'id' => $id ] );
    }
@endphp
@if ( ! $hasLabel && 'horizontal' === $orientation )
<hr {{ $rootAttributes }} data-orientation="horizontal" data-strength="{{ $strength }}" data-align="{{ $align }}"@if ( $decorative ) data-decorative aria-hidden="true"@endif>
@elseif ( ! $hasLabel )
<div {{ $rootAttributes }} role="separator" aria-orientation="vertical" data-orientation="vertical" data-strength="{{ $strength }}" data-align="{{ $align }}"@if ( $decorative ) data-decorative aria-hidden="true"@endif></div>
@else
@php $labelId = $labelId(); @endphp
<div {{ $rootAttributes }}@if ( $decorative ) role="none" data-decorative @else role="separator" aria-orientation="{{ $orientation }}" aria-labelledby="{{ $labelId }}" @endif data-orientation="{{ $orientation }}" data-strength="{{ $strength }}" data-align="{{ $align }}"><span data-scope="divider" data-part="label" id="{{ $labelId }}"{{ $partClass( 'label' ) }}>{{ $content( $labelContent ) }}</span></div>
@endif
