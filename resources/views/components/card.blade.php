{{--
    Card (#48). Markup contract: design-system pilot, Card.
    Named slots (media, title, description, actions, footer) override the props.
    href links the title, so it needs a title; unsafe href and media schemes are ignored.
--}}
@php
    $mediaSlot      = $media instanceof \Illuminate\Contracts\Support\Htmlable ? $media : null;
    $mediaSrc       = null === $mediaSlot ? $mediaSrc() : null;
    $hasMedia       = null !== $mediaSlot ? $filled( $mediaSlot ) : null !== $mediaSrc;
    $hasTitle       = $filled( $title );
    $hasDescription = $filled( $description );
    $hasActions     = $filled( $actions );
    $hasFooter      = $filled( $footer );
    $hasBody        = isset( $slot ) && $slot->hasActualContent();
    $linkHref       = $linkHref();
    $hasLink        = null !== $linkHref && $hasTitle;
@endphp
<div
    {{ $attributes->class( $rootClasses( 'apui-card' ) ) }}
    data-surface="{{ $surface }}"
    @if ( $interactive || $hasLink ) data-interactive @endif
>
    @if ( $hasMedia )
        @if ( null !== $mediaSlot )
            <div data-scope="card" data-part="media"{{ $partClass( 'media' ) }}>{{ $content( $mediaSlot ) }}</div>
        @else
            <img data-scope="card" data-part="media" src="{{ $mediaSrc }}" alt="{{ $mediaAlt }}"{{ $partClass( 'media' ) }} />
        @endif
    @endif
    @if ( $hasTitle || $hasDescription || $hasActions )
        <div data-scope="card" data-part="header"{{ $partClass( 'header' ) }}>
            @if ( $hasTitle || $hasDescription )
                <div>
                    @if ( $hasTitle )
                        <h3 data-scope="card" data-part="title"{{ $partClass( 'title' ) }}>@if ( $hasLink )<a href="{{ $linkHref }}">{{ $content( $title ) }}</a>@else{{ $content( $title ) }}@endif</h3>
                    @endif
                    @if ( $hasDescription )
                        <p data-scope="card" data-part="description"{{ $partClass( 'description' ) }}>{{ $content( $description ) }}</p>
                    @endif
                </div>
            @endif
            @if ( $hasActions )
                <div data-scope="card" data-part="actions"{{ $partClass( 'actions' ) }}>{{ $content( $actions ) }}</div>
            @endif
        </div>
    @endif
    @if ( $hasBody )
        <div data-scope="card" data-part="body"{{ $partClass( 'body' ) }}>{{ $content( $slot ) }}</div>
    @endif
    @if ( $hasFooter )
        <div data-scope="card" data-part="footer"{{ $partClass( 'footer' ) }}>{{ $content( $footer ) }}</div>
    @endif
</div>
