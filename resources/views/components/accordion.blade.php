{{--
    Accordion (#58). Markup contract: design-system Accordion. The .apui-accordion
    root is the machine's root part; items from `items` render first, then the
    accordion-item children from the slot. Each item renders its own title,
    trigger and panel with stable ids per value (D-015). Attributes land on the
    root, where value-change is dispatched.
--}}
@php
    $baseId = $baseId();
    ob_start();
@endphp
@foreach ( $entries() as $entry )
    <x-artisanpack-accordion-item :value="$entry['value']" :title="$entry['title']" :disabled="$entry['disabled']">{{ $entry['content'] }}</x-artisanpack-accordion-item>
@endforeach
@php
    $dataItems = new \Illuminate\Support\HtmlString( (string) ob_get_clean() );
@endphp
<div
    {{ $rootAttributes()->class( $rootClasses( 'apui-accordion' ) ) }}
    id="{{ $baseId }}"
    data-scope="accordion"
    data-part="root"
    data-orientation="vertical"
    data-variant="{{ $variant }}"
    @if ( $multiple ) data-multiple @endif
    @if ( $collapsible ) data-collapsible @endif
    x-data="{{ $alpineData() }}"
    x-apui:root
>
    {{ $dataItems }}
    {{ $slot ?? '' }}
</div>
@php $finishRendering(); @endphp
