{{--
    Tab (#56). Markup contract: design-system Tabs. The panel, Zag's content
    part: role tabpanel, labelled by its trigger, hidden while another tab is
    selected. The trigger is rendered by the Tabs, which this tab registered
    with. Every attribute lands on the panel.
--}}
@php
    $tabs = $parent();
@endphp
@unless ( $hidden )
    @php
        $tabValue = $tabValue();
        $selected = null === $tabs || $tabs->isSelected( $tabValue );
        $panelClasses = array_values( array_filter( [ $classes['content'] ?? null, $contentClasses ], fn ( $class ) => $filled( $class ) ) );
    @endphp
    <div
        {{ $attributes->when( [] !== $panelClasses, fn ( $bag ) => $bag->class( $panelClasses ) ) }}
        @if ( null !== $tabs )
            id="{{ $tabs->valuePartId( 'content', $tabValue ) }}"
            aria-labelledby="{{ $tabs->valuePartId( 'trigger', $tabValue ) }}"
            data-orientation="{{ $tabs->orientation }}"
            x-apui:content="{{ $tabs->partArgs( [ 'value' => $tabValue ] ) }}"
        @endif
        data-scope="tabs"
        data-part="content"
        role="tabpanel"
        tabindex="0"
        @if ( $selected ) data-selected @else hidden @endif
    >{{ $slot }}</div>
@endunless
