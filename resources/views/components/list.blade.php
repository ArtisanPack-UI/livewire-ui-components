{{--
    List (#58). Markup contract: design-system wave, ListItem (container).
    A ul (or ol) with data-variant: plain, bordered or divided. Its list-item
    children render as li.
--}}
@php $tag = $ordered ? 'ol' : 'ul'; @endphp
<{{ $tag }} {{ $attributes->class( $rootClasses( 'apui-list' ) ) }} data-variant="{{ $variantValue() }}">{{ $slot ?? '' }}</{{ $tag }}>
@php $finishRendering(); @endphp
