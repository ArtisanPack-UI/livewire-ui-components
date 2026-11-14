{{--
    Collapse (#58). Markup contract: design-system Collapse. The .apui-collapse
    root (Zag's root, rescoped collapse) holds the trigger and the region. A
    section renders the trigger first and hides the closed region; a read-more
    renders the region first, clamped behind the fade while closed, then the
    trigger. Every part has a stable, server-rendered id (D-015). The 2.x
    `heading` and `content` slots still render (deprecated): `$content` is then
    the slot, not the parts helper, so this view doesn't call it.
--}}
@php
    $baseId    = $baseId();
    $isOpen    = $isOpen();
    $state     = $isOpen ? 'open' : 'closed';
    $readMore  = $isReadMore();
    $body      = ( isset( $content ) && $content instanceof \Illuminate\View\ComponentSlot ) ? $content : ( $slot ?? '' );
    $closedText = isset( $trigger ) && $trigger instanceof \Illuminate\View\ComponentSlot ? $trigger : ( ( isset( $heading ) && $heading instanceof \Illuminate\View\ComponentSlot ) ? $heading : $closedLabel() );
    $openText  = $expandedLabel();
    $shown     = $isOpen && null !== $openText ? $openText : $closedText;
    $toHtml    = static fn ( $value ) => $value instanceof \Illuminate\Contracts\Support\Htmlable ? trim( $value->toHtml() ) : e( (string) $value );
    $clamp     = 'var(--apui-collapse-clamp-height, 64px)';
@endphp
<div
    {{ $rootAttributes()->class( $rootClasses( 'apui-collapse' ) ) }}
    id="{{ $baseId }}"
    data-scope="collapse"
    data-part="root"
    data-state="{{ $state }}"
    data-variant="{{ $variant }}"
    x-data="{{ $alpineData() }}"
    x-apui:root
>
    @php
        ob_start();
    @endphp
    <button
        type="button"
        id="{{ $partId( 'trigger' ) }}"
        data-scope="collapse"
        data-part="trigger"
        data-state="{{ $state }}"
        aria-controls="{{ $partId( 'content' ) }}"
        aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
        @if ( $disabled ) data-disabled disabled @endif
        {{ $partClass( 'trigger' ) }}
        x-apui:trigger
    >@if ( null !== $openText )<span x-text="open ? {{ \Illuminate\Support\Js::from( strip_tags( $toHtml( $openText ) ) ) }} : {{ \Illuminate\Support\Js::from( strip_tags( $toHtml( $closedText ) ) ) }}">{!! $toHtml( $shown ) !!}</span>@else{!! $toHtml( $shown ) !!}@endif<span data-scope="collapse" data-part="icon" data-state="{{ $state }}" aria-hidden="true"@if ( $disabled ) data-disabled @endif{{ $partClass( 'icon' ) }} x-apui:indicator>{{ $iconSvg( 'chevron-down' ) }}</span></button>
    @php
        $triggerHtml = new \Illuminate\Support\HtmlString( (string) ob_get_clean() );
    @endphp
    @unless ( $readMore )
        {{ $triggerHtml }}
    @endunless
    <div
        id="{{ $partId( 'content' ) }}"
        data-scope="collapse"
        data-part="content"
        data-collapsible=""
        data-state="{{ $state }}"
        @if ( $readMore )
            @unless ( $isOpen ) data-has-collapsed-size style="overflow: hidden; max-height: {{ $clamp }}" @endunless
        @else
            @unless ( $isOpen ) hidden @endunless
        @endif
        {{ $partClass( 'content' ) }}
        x-apui:content
    >{{ $body }}@if ( $readMore )<span data-scope="collapse" data-part="fade" aria-hidden="true"{{ $partClass( 'fade' ) }}></span>@endif</div>
    @if ( $readMore )
        {{ $triggerHtml }}
    @endif
</div>
