{{--
    Tooltip (#59). Markup contract: design-system Tooltip (D-019).
    The default slot's first element is the trigger part; the .apui-tooltip label
    is Zag's content part (role=tooltip), scoped tooltip. Every part has a stable,
    server-rendered id, so Livewire's morph keeps them (D-015). The hidden label
    still renders. `class` lands on the label; every other attribute lands on the
    apuiTooltip element, where open-change is dispatched.
--}}
@php
    $baseId    = $baseId();
    $shown     = $shown();
    $state     = $shown ? 'open' : 'closed';
    $labelSlot = isset( $content ) && $content instanceof \Illuminate\Contracts\Support\Htmlable && $filled( $content ) ? $content : null;
    $keys      = $keys();
@endphp
<span {{ $hostAttributes() }} id="{{ $baseId }}" x-data="{{ $alpineData() }}" style="display: contents">
    {{ $triggerMarkup( $slot ?? '' ) }}
    @if ( $portal )<template x-teleport="body">@endif
    <div data-scope="tooltip" data-part="positioner" id="{{ $partId( 'positioner' ) }}"{{ $partClass( 'positioner' ) }} x-apui:positioner>
        <div
            {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-tooltip' ) ) }}
            data-scope="tooltip"
            data-part="content"
            id="{{ $partId( 'content' ) }}"
            role="tooltip"
            data-state="{{ $state }}"
            data-placement="{{ $resolvedPlacement() }}"
            data-size="{{ 'lg' === $size ? 'lg' : 'md' }}"
            @if ( $disabled ) data-disabled @endif
            @if ( ! $shown ) hidden @endif
            x-apui:content
        >
            <span data-scope="tooltip" data-part="arrow" id="{{ $partId( 'arrow' ) }}" aria-hidden="true"{{ $partClass( 'arrow' ) }} x-apui:arrow></span>{{ null !== $labelSlot ? $markup( $labelSlot ) : $label() }}@foreach ( $keys as $index => $key )<kbd data-scope="tooltip" data-part="kbd" id="{{ $partId( 'kbd-'.( $index + 1 ) ) }}"{{ $partClass( 'kbd' ) }}>{{ $key }}</kbd>@endforeach
        </div>
    </div>
    @if ( $portal )</template>@endif
</span>
