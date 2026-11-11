{{--
    Fieldset (#53). Markup contract: design-system form-text wave, Fieldset.
    A native fieldset and legend, so the group's name is announced with each
    field. Attributes land on the fieldset; disabled disables every control
    inside.
--}}
@php
    $hasTitle       = $filled( $title );
    $hasDescription = $filled( $description );
@endphp
<fieldset
    {{ $attributes->class( $rootClasses( 'apui-fieldset' ) ) }}
    data-variant="{{ $variant }}"
    @if ( $disabled ) disabled data-disabled @endif
>
    @if ( $hasTitle || $hasDescription )
        <legend data-scope="fieldset" data-part="legend"{{ $partClass( 'legend' ) }}>@if ( $hasTitle )<span data-scope="fieldset" data-part="title"{{ $partClass( 'title' ) }}>{{ $content( $title ) }}</span>@endif @if ( $hasDescription )<span data-scope="fieldset" data-part="description"{{ $partClass( 'description' ) }}>{{ $content( $description ) }}</span>@endif</legend>
    @endif
    {{ $slot }}
</fieldset>
