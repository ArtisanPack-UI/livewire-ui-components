{{--
    StreamableContent (#60, Livewire only per D-003). Markup contract:
    design-system wave #58–#62, StreamableContent. data-state drives the
    status line, the waiting skeleton, the caret and Stop, which dispatches
    stop (wire:stop="cancel"). The content is aria-live=polite and aria-busy
    while waiting or streaming. wire:stream works on Livewire 3 and 4.
--}}
@php
    $statusSlot = isset( $status ) && $status instanceof \Illuminate\Contracts\Support\Htmlable && $filled( $status ) ? $status : null;
    $statusIcon = $statusIcon();
    $hasContent = $filled( $slot );
@endphp
<div
    {{ $attributes->except( 'id' )->class( $rootClasses( 'apui-streamable-content' ) ) }}
    x-data
    data-scope="streamable-content"
    data-part="root"
    id="{{ $baseId() }}"
    data-state="{{ $state }}"
>
    <div data-scope="streamable-content" data-part="status" role="status"{{ $partClass( 'status' ) }}>
        @if ( 'waiting' === $state )
            <span class="apui-loading" data-variant="spinner" data-size="xs" data-color="primary" aria-hidden="true"><span data-scope="loading" data-part="indicator"></span></span>
        @elseif ( 'streaming' === $state )
            <span class="apui-loading" data-variant="dots" data-size="xs" data-color="primary" aria-hidden="true"><span data-scope="loading" data-part="indicator"><span></span><span></span><span></span></span></span>
        @elseif ( null !== $statusIcon )
            {{ $iconSvg( $statusIcon ) }}
        @endif
        @if ( null !== $statusSlot ){{ $content( $statusSlot ) }}@else{{ $statusText() }}@endif
        @if ( 'streaming' === $state )
            <x-artisanpack-button
                size="xs"
                variant="outline"
                color="neutral"
                leading-icon="square"
                :class="$classes['stopTrigger'] ?? null"
                data-scope="streamable-content"
                data-part="stop-trigger"
                x-on:click="$dispatch( 'stop' )"
            >{{ $stopText() }}</x-artisanpack-button>
        @endif
    </div>
    <div data-scope="streamable-content" data-part="content" id="{{ $partId( 'content' ) }}" aria-live="polite" aria-busy="{{ $isBusy() ? 'true' : 'false' }}"{{ $partClass( 'content' ) }}>
        <span id="{{ $partId( 'stream' ) }}" @if ( $supportsStreaming() ) wire:stream="{{ $target }}" @endif>@if ( $hasContent ){{ $content( $slot ) }}@endif</span>
        @if ( 'waiting' === $state && ! $hasContent )
            <span class="apui-skeleton" data-shape="text" aria-hidden="true" style="width: 92%"></span><span class="apui-skeleton" data-shape="text" aria-hidden="true" style="width: 84%"></span><span class="apui-skeleton" data-shape="text" aria-hidden="true" style="width: 56%"></span>
        @endif
        @if ( 'streaming' === $state )
            <span data-scope="streamable-content" data-part="caret" aria-hidden="true"{{ $partClass( 'caret' ) }}></span>
        @endif
    </div>
    @if ( $filled( $actions ) )
        <div data-scope="streamable-content" data-part="actions"{{ $partClass( 'actions' ) }}>{{ $content( $actions ) }}</div>
    @endif
</div>
