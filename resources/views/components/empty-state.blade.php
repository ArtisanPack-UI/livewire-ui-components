{{--
    EmptyState (#60). Markup contract: design-system wave #58–#62, EmptyState.
    Named slots (icon, title, description, actions) override the props; the
    default slot fills the description.
--}}
@php
    $iconSlot        = isset( $icon ) && $icon instanceof \Illuminate\Contracts\Support\Htmlable ? $icon : null;
    $descriptionBody = $filled( $description ) ? $description : ( $filled( $slot ) ? $slot : null );
    $headingTag      = $headingTag();
@endphp
<div
    {{ $attributes->class( $rootClasses( 'apui-empty-state' ) ) }}
    data-scope="empty-state"
    data-part="root"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    data-align="{{ $align }}"
>
    <span data-scope="empty-state" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $iconName() ) }}@endif</span>
    @if ( $filled( $title ) )
        <{{ $headingTag }} data-scope="empty-state" data-part="title"{{ $partClass( 'title' ) }}>{{ $content( $title ) }}</{{ $headingTag }}>
    @endif
    @if ( null !== $descriptionBody )
        <div data-scope="empty-state" data-part="description"{{ $partClass( 'description' ) }}>{{ $content( $descriptionBody ) }}</div>
    @endif
    @if ( $filled( $actions ) )
        <div data-scope="empty-state" data-part="actions"{{ $partClass( 'actions' ) }}>{{ $content( $actions ) }}</div>
    @endif
</div>
