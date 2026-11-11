{{--
    Step (#56). Markup contract: design-system wave 4, Steps.
    A step part: the marker (number, check, x or icon, plus hidden state text),
    the title (a button on completed steps when clickable), the connector and
    the description. Every attribute lands on the step.
--}}
@php
    $state      = $stepState();
    $stateLabel = $stateLabel();
    $markerIcon = $filled( $icon ) ? $icon : match ( $state ) {
        'complete' => 'check',
        'error'    => 'x',
        default    => null,
    };
    $titleHtml  = isset( $slot ) && $slot->hasActualContent() ? $content( $slot ) : $content( $titleText() );
@endphp
<li
    {{ $attributes->when( $filled( $classes['step'] ?? null ), fn ( $bag ) => $bag->class( $classes['step'] ) ) }}
    data-scope="steps"
    data-part="step"
    data-state="{{ $state }}"
    @if ( 'current' === $state ) aria-current="step" @endif
>
    <span data-scope="steps" data-part="marker"{{ $partClass( 'marker' ) }}>@if ( null !== $markerIcon ){{ $iconSvg( $markerIcon ) }}@else<span aria-hidden="true">{{ $number }}</span>@endif @if ( null !== $stateLabel )<span class="apui-sr-only">{{ $stateLabel }}</span>@endif</span>
    @if ( $isButton() )
        <button type="button" data-scope="steps" data-part="title"{{ $partClass( 'title' ) }} x-on:click="value = {{ $number }}">{{ $titleHtml }}</button>
    @else
        <span data-scope="steps" data-part="title"{{ $partClass( 'title' ) }}>{{ $titleHtml }}</span>
    @endif
    <span data-scope="steps" data-part="connector" aria-hidden="true"{{ $partClass( 'connector' ) }}></span>
    @if ( $filled( $description ) )
        <span data-scope="steps" data-part="description"{{ $partClass( 'description' ) }}>{{ $description }}</span>
    @endif
</li>
