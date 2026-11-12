{{--
    ErrorState (#60). Markup contract: design-system wave #58–#62, ErrorState.
    role=alert, so an error that replaces content is announced. The icon
    tile shows for an explicit icon, or the variant's when there's no numeral.
    Named slots (icon, title, description, actions) override the props; the
    default slot fills the description.
--}}
@php
    $iconSlot        = isset( $icon ) && $icon instanceof \Illuminate\Contracts\Support\Htmlable ? $icon : null;
    $iconName        = null === $iconSlot ? $iconName() : null;
    $numeralText     = $numeralText();
    $descriptionBody = $filled( $description ) ? $description : ( $filled( $slot ) ? $slot : null );
    $headingTag      = $headingTag();
@endphp
<div
    {{ $attributes->class( $rootClasses( 'apui-error-state' ) ) }}
    data-scope="error-state"
    data-part="root"
    role="alert"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    data-align="{{ $align }}"
>
    @if ( null !== $iconSlot || null !== $iconName )
        <span data-scope="error-state" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $iconName ) }}@endif</span>
    @endif
    @if ( null !== $numeralText )
        <span data-scope="error-state" data-part="numeral"{{ $partClass( 'numeral' ) }}>{{ $numeralText }}</span>
    @endif
    @if ( $filled( $title ) )
        <{{ $headingTag }} data-scope="error-state" data-part="title"{{ $partClass( 'title' ) }}@if ( $focusTitle ) tabindex="-1" data-focus-title x-data x-init="$el.focus()"@endif>{{ $content( $title ) }}</{{ $headingTag }}>
    @endif
    @if ( null !== $descriptionBody )
        <div data-scope="error-state" data-part="description"{{ $partClass( 'description' ) }}>{{ $content( $descriptionBody ) }}</div>
    @endif
    @if ( $filled( $code ) )
        <span data-scope="error-state" data-part="code"{{ $partClass( 'code' ) }}>{{ $code }}</span>
    @endif
    @if ( $filled( $actions ) )
        <div data-scope="error-state" data-part="actions"{{ $partClass( 'actions' ) }}>{{ $content( $actions ) }}</div>
    @endif
</div>
