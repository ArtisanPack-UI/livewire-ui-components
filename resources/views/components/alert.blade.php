{{--
    Alert (#60). Markup contract: design-system wave #58–#62, Alert.
    role=alert for errors, role=status otherwise. Named slots (icon, title,
    description, actions) override the props; the default slot fills the
    description. A dismissible alert hides itself and dispatches open-change.
--}}
@php
    $iconSlot        = isset( $icon ) && $icon instanceof \Illuminate\Contracts\Support\Htmlable ? $icon : null;
    $descriptionBody = $filled( $description ) ? $description : ( $filled( $slot ) ? $slot : null );
@endphp
<div
    {{ $attributes->except( 'id' )->class( $rootClasses( 'apui-alert' ) ) }}
    x-data="{ open: true }"
    x-bind:hidden="! open"
    data-scope="alert"
    data-part="root"
    id="{{ $baseId() }}"
    role="{{ $role() }}"
    data-color="{{ $color }}"
    data-variant="{{ $variant }}"
    data-surface="{{ $surface }}"
>
    <span data-scope="alert" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>@if ( null !== $iconSlot ){{ $content( $iconSlot ) }}@else{{ $iconSvg( $iconName() ) }}@endif</span>
    @if ( $filled( $title ) )
        <p data-scope="alert" data-part="title"{{ $partClass( 'title' ) }}>{{ $content( $title ) }}</p>
    @endif
    @if ( null !== $descriptionBody )
        <div data-scope="alert" data-part="description"{{ $partClass( 'description' ) }}>{{ $content( $descriptionBody ) }}</div>
    @endif
    @if ( $filled( $actions ) )
        <div data-scope="alert" data-part="actions"{{ $partClass( 'actions' ) }}>{{ $content( $actions ) }}</div>
    @endif
    @if ( $dismissible )
        <x-artisanpack-button
            variant="ghost"
            color="neutral"
            size="xs"
            icon-only
            leading-icon="x"
            :aria-label="$closeTriggerLabel()"
            :class="$classes['closeTrigger'] ?? null"
            data-scope="alert"
            data-part="close-trigger"
            x-on:click="open = false; $dispatch( 'open-change', { open: false } )"
        />
    @endif
</div>
