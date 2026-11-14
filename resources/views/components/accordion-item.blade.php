{{--
    AccordionItem (#58). Markup contract: design-system Accordion. The item, its
    title (h2–h6 at the Accordion's headingLevel) holding the trigger and its
    chevron, then the panel (Zag's item-content, rendered as content): role
    region, labelled by the trigger, hidden while closed. Ids and state come
    from the Accordion (D-015). Every attribute lands on the item.
--}}
@php
    $accordion = $parent();
    $itemValue = $itemValue();
    $isOpen    = null !== $accordion && $accordion->isItemOpen( $itemValue );
    $isOff     = $disabled || ( null !== $accordion && $accordion->disabled );
    $state     = $isOpen ? 'open' : 'closed';
    $level     = $accordion?->headingLevel ?? 3;
    $triggerId = $accordion?->valuePartId( 'trigger', $itemValue );
    $contentId = $accordion?->valuePartId( 'content', $itemValue );
    $args      = $accordion?->partArgs( [ 'value' => $itemValue, 'disabled' => $disabled ] );
@endphp
<div
    {{ $attributes->when( $partClasses( 'item' ), fn ( $bag, $classes ) => $bag->class( $classes ) ) }}
    @if ( null !== $accordion )
        id="{{ $accordion->valuePartId( 'item', $itemValue ) }}"
        wire:key="{{ $accordion->valuePartId( 'item', $itemValue ) }}"
        x-apui:item="{{ $args }}"
    @endif
    data-scope="accordion"
    data-part="item"
    data-state="{{ $state }}"
    data-orientation="vertical"
    @if ( $isOff ) data-disabled @endif
>
    <h{{ $level }} data-scope="accordion" data-part="title"{{ $partClass( 'title' ) }}>
        <button
            type="button"
            data-scope="accordion"
            data-part="trigger"
            @if ( null !== $accordion )
                id="{{ $triggerId }}"
                aria-controls="{{ $contentId }}"
                data-controls="{{ $contentId }}"
                data-ownedby="{{ $accordion->baseId() }}"
                x-apui:item-trigger="{{ $args }}"
            @endif
            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
            data-state="{{ $state }}"
            data-orientation="vertical"
            @if ( $isOff ) disabled @endif
            {{ $partClass( 'trigger' ) }}
        >{{ $content( $title ?? $itemValue ) }}<span data-scope="accordion" data-part="chevron" aria-hidden="true" data-state="{{ $state }}" data-orientation="vertical"{{ $partClass( 'chevron' ) }}@if ( null !== $accordion ) x-apui:item-indicator="{{ $accordion->partArgs( [ 'value' => $itemValue ] ) }}"@endif>{{ $iconSvg( 'chevron-down' ) }}</span></button>
    </h{{ $level }}>
    <div
        data-scope="accordion"
        data-part="content"
        role="region"
        @if ( null !== $accordion )
            id="{{ $contentId }}"
            aria-labelledby="{{ $triggerId }}"
            x-apui:item-content="{{ $accordion->partArgs( [ 'value' => $itemValue ] ) }}"
        @endif
        data-state="{{ $state }}"
        data-orientation="vertical"
        @unless ( $isOpen ) hidden @endunless
        {{ $partClass( 'content' ) }}
    >{{ $slot }}</div>
</div>
