{{--
    Toast (#51). Markup contract: design-system pilot, Toast.
    A toast on its own is static: the attributes of staticToastParts() in the
    behavior package, less the machine. The close button dispatches open-change;
    hiding the toast is the owner's job. The toaster renders live toasts.
    Named slots (icon, title, description, actions) override the props. A bare
    <x-artisanpack-toast /> (the 2.x toaster) renders nothing and raises a deprecation.
--}}
@php
    $iconSlot       = isset( $icon ) && $icon instanceof \Illuminate\Contracts\Support\Htmlable ? $icon : null;
    $hasTitle       = $filled( $title );
    $hasDescription = $filled( $description );
    $hasActions     = $filled( $actions );
    $legacy         = $isLegacyToaster( $hasTitle || $hasDescription || $hasActions || null !== $iconSlot );
@endphp
@unless ( $legacy )
<div
    {{ $attributes->except( 'id' )->class( $rootClasses( 'apui-toast' ) ) }}
    x-data
    data-scope="toast"
    data-part="root"
    id="{{ $partId( 'root' ) }}"
    data-state="open"
    data-type="{{ $toastType() }}"
    role="{{ 'error' === $color ? 'alert' : 'status' }}"
    aria-atomic="true"
    @if ( $hasTitle ) aria-labelledby="{{ $partId( 'title' ) }}" @endif
    @if ( $hasDescription ) aria-describedby="{{ $partId( 'description' ) }}" @endif
    data-color="{{ $color }}"
    data-variant="{{ $variant }}"
    data-surface="{{ $surface }}"
>
    <span data-scope="toast" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $iconName() ) }}@endif</span>
    @if ( $hasTitle )
        <p data-scope="toast" data-part="title" id="{{ $partId( 'title' ) }}"{{ $partClass( 'title' ) }}>{{ $content( $title ) }}</p>
    @endif
    @if ( $hasDescription )
        <p data-scope="toast" data-part="description" id="{{ $partId( 'description' ) }}"{{ $partClass( 'description' ) }}>{{ $content( $description ) }}</p>
    @endif
    @if ( $hasActions )
        <div data-scope="toast" data-part="actions"{{ $partClass( 'actions' ) }}>{{ $content( $actions ) }}</div>
    @endif
    @if ( ! $hideClose )
        <x-artisanpack-button
            variant="ghost"
            color="neutral"
            size="sm"
            icon-only
            leading-icon="x"
            :aria-label="$closeTriggerLabel()"
            :class="$classes['closeTrigger'] ?? null"
            data-scope="toast"
            data-part="close-trigger"
            x-on:click="$dispatch( 'open-change', { open: false } )"
        />
    @endif
</div>
@endunless
