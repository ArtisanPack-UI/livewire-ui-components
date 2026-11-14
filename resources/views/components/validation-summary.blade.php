{{--
    ValidationSummary (#53). Markup contract: design-system form-text wave,
    ValidationSummary. Lists the errors (the `errors` prop, else $errors),
    each linking to #field. Renders nothing without errors. An error summary
    is an alert and takes focus when it appears; each link moves focus to its
    field: the element with that id, else the control with that name or
    wire:model.
--}}
@php
    $entries = $entries();
    $isError = 'warning' !== $color;
@endphp
@if ( [] !== $entries )
    <div
        {{ $attributes->class( $rootClasses( 'apui-validation-summary' ) ) }}
        data-color="{{ $color }}"
        role="{{ $isError ? 'alert' : 'status' }}"
        @if ( $isError ) tabindex="-1" x-data x-init="$nextTick(() => $el.focus())" @else x-data @endif
    >
        <span data-scope="validation-summary" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>{{ $iconSvg( $isError ? 'circle-alert' : 'triangle-alert' ) }}</span>
        <p data-scope="validation-summary" data-part="title"{{ $partClass( 'title' ) }}>{{ $filled( $title ) ? $content( $title ) : $defaultTitle( count( $entries ) ) }}</p>
        <ul data-scope="validation-summary" data-part="list"{{ $partClass( 'list' ) }}>
            @foreach ( $entries as $entry )
                <li data-scope="validation-summary" data-part="item" wire:key="apui-validation-summary-{{ $loop->index }}-{{ md5( $entry['field'] ) }}"{{ $partClass( 'item' ) }}><a data-scope="validation-summary" data-part="link" href="#{{ $entry['field'] }}"{{ $partClass( 'link' ) }} x-on:click="const field = @js( $entry['field'] ); const target = document.getElementById(field) ?? document.querySelector(`[name='${CSS.escape(field)}']`) ?? [ ...document.querySelectorAll('input, select, textarea, button, [tabindex]') ].find((el) => [ ...el.attributes ].some((attr) => attr.name.startsWith('wire:model') && attr.value === field)); if (target) { $event.preventDefault(); target.focus(); target.scrollIntoView({ block: 'center' }) }">{{ $entry['message'] }}</a></li>
            @endforeach
        </ul>
    </div>
@endif
