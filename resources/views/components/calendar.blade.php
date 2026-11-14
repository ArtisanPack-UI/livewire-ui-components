{{--
    Calendar (#64). Markup contract: design-system wave 7, Calendar.
    Rendered on the server from CalendarModel, the port of behavior's
    calendarModel, so the days, labels and event order match React and Vue.
    The controls dispatch view-change, date-change, event-click and create;
    the Livewire Calendar answers them. Each event is a button that dispatches
    event-click, or a link with a safe href, named by its title, category and
    full date and time, never by its tint. Alpine (apuiCalendar) adds the arrow
    keys, which follow the text direction (D-004).
--}}
@php
    $model   = $model();
    $labels  = $model['labels'];
    $id      = $baseId();
    $titleId = $id . '-title';
    $bodyId  = $id . '-body';
    $tabId   = static fn ( string $view ): string => $id . '-tab-' . $view;
@endphp
<div
    {{ $attributes->except( [ 'id' ] )->class( $rootClasses( 'apui-calendar' ) ) }}
    id="{{ $id }}"
    data-view="{{ $model['view'] }}"
    x-data="apuiCalendar()"
>
    <div data-scope="calendar" data-part="toolbar"{{ $partClass( 'toolbar' ) }}>
        <x-artisanpack-button
            variant="outline"
            color="neutral"
            size="sm"
            data-scope="calendar"
            data-part="today-trigger"
            :class="$classes['todayTrigger'] ?? null"
            x-on:click="$dispatch('date-change', {{ $js( $model['today'] ) }})"
        >{{ $labels['today'] }}</x-artisanpack-button>
        <x-artisanpack-button
            variant="ghost"
            color="neutral"
            size="sm"
            icon-only
            leading-icon="chevron-left"
            :aria-label="$labels['previous']"
            data-scope="calendar"
            data-part="previous-trigger"
            :class="$classes['previousTrigger'] ?? null"
            x-on:click="$dispatch('date-change', {{ $js( $model['previousDate'] ) }})"
        />
        <x-artisanpack-button
            variant="ghost"
            color="neutral"
            size="sm"
            icon-only
            leading-icon="chevron-right"
            :aria-label="$labels['next']"
            data-scope="calendar"
            data-part="next-trigger"
            :class="$classes['nextTrigger'] ?? null"
            x-on:click="$dispatch('date-change', {{ $js( $model['nextDate'] ) }})"
        />
        <h2 data-scope="calendar" data-part="title" id="{{ $titleId }}" aria-live="polite"{{ $partClass( 'title' ) }}>{{ $model['title'] }}</h2>
        <div class="apui-tabs{{ isset( $classes['viewTabs'] ) ? ' ' . $classes['viewTabs'] : '' }}" data-variant="segmented" data-size="sm" data-scope="calendar" data-part="view-tabs">
            <div data-scope="tabs" data-part="list" role="tablist" aria-label="{{ $labels['views'] }}" x-on:keydown="tabsKeydown($event)">
                @foreach ( \ArtisanPack\LivewireUiComponents\Support\CalendarModel::VIEWS as $option )
                    <button
                        type="button"
                        data-scope="tabs"
                        data-part="trigger"
                        role="tab"
                        id="{{ $tabId( $option ) }}"
                        aria-selected="{{ $option === $model['view'] ? 'true' : 'false' }}"
                        aria-controls="{{ $bodyId }}"
                        tabindex="{{ $option === $model['view'] ? '0' : '-1' }}"
                        @if ( $option === $model['view'] ) data-selected @endif
                        x-on:click="$dispatch('view-change', {{ $js( $option ) }})"
                    >{{ $labels[ $option ] }}</button>
                @endforeach
            </div>
        </div>
        @if ( $creatable )
            <x-artisanpack-button
                size="sm"
                leading-icon="plus"
                data-scope="calendar"
                data-part="create-trigger"
                :class="$classes['createTrigger'] ?? null"
                x-on:click="$dispatch('create', {{ $js( $model['date'] ) }})"
            >{{ $labels['newEvent'] }}</x-artisanpack-button>
        @endif
    </div>
    <div data-scope="calendar" data-part="body" id="{{ $bodyId }}" role="tabpanel" aria-labelledby="{{ $tabId( $model['view'] ) }}"{{ $partClass( 'body' ) }}>
        @if ( 'agenda' === $model['view'] )
            @if ( [] === $model['agenda'] )
                <x-artisanpack-empty-state size="sm" icon="calendar" :title="$labels['empty']" />
            @else
                <ul data-scope="calendar" data-part="grid" aria-labelledby="{{ $titleId }}"{{ $partClass( 'grid' ) }}>
                    @foreach ( $model['agenda'] as $agendaDay )
                        <li data-scope="calendar" data-part="row" data-date="{{ $agendaDay['date'] }}" @if ( $agendaDay['today'] ) data-today @endif wire:key="{{ $id }}-{{ $agendaDay['date'] }}"{{ $partClass( 'row' ) }}>
                            <h3 data-scope="calendar" data-part="weekday" id="{{ $id }}-{{ $agendaDay['date'] }}" @if ( $agendaDay['today'] ) data-today @endif{{ $partClass( 'weekday' ) }}>{{ $agendaDay['label'] }}</h3>
                            <ul data-scope="calendar" data-part="cell" aria-labelledby="{{ $id }}-{{ $agendaDay['date'] }}"{{ $partClass( 'cell' ) }}>
                                @foreach ( $agendaDay['events'] as $item )
                                    <li wire:key="{{ $id }}-{{ $item['key'] }}">
                                        @php
                                            $time           = $item['range'];
                                            $eventHrefValue = $eventHref( $item['href'] );
                                            $eventTag       = null !== $eventHrefValue ? 'a' : 'button';
                                        @endphp
                                        <{{ $eventTag }}
                                            @if ( 'a' === $eventTag ) href="{{ $eventHrefValue }}" @else type="button" @endif
                                            data-scope="calendar"
                                            data-part="event"
                                            data-color="{{ $item['color'] }}"
                                            data-event-key="{{ $item['eventKey'] }}"
                                            @if ( 'button' === $eventTag && $details ) aria-haspopup="dialog" @endif
                                            @if ( 'button' === $eventTag ) x-on:click="$dispatch('event-click', {{ $js( $item['event'] ) }})" @endif
                                            wire:key="{{ $id }}-event-{{ $item['key'] }}"
                                            {{ $partClass( 'event' ) }}
                                        >@if ( '' !== $time )<span data-scope="calendar" data-part="event-time"{{ $partClass( 'eventTime' ) }}>{{ $time }}</span>@endif<span data-scope="calendar" data-part="event-title"{{ $partClass( 'eventTitle' ) }}>{{ $item['title'] }}</span><span class="apui-sr-only">, {{ $item['context'] }}</span></{{ $eventTag }}>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            @endif
        @else
            <div data-scope="calendar" data-part="grid" role="grid" aria-labelledby="{{ $titleId }}" x-on:keydown="gridKeydown($event)"{{ $partClass( 'grid' ) }}>
                <div data-scope="calendar" data-part="row" role="row"{{ $partClass( 'row' ) }}>
                    @foreach ( $model['weekdays'] as $weekday )
                        <div data-scope="calendar" data-part="weekday" role="columnheader" @if ( $weekday['today'] ) data-today @endif{{ $partClass( 'weekday' ) }}>{{ $weekday['short'] }}@if ( null !== $weekday['day'] ) <span data-scope="calendar" data-part="date"{{ $partClass( 'date' ) }}>{{ $weekday['day'] }}</span>@endif</div>
                    @endforeach
                </div>
                @foreach ( $model['rows'] as $row )
                    <div data-scope="calendar" data-part="row" role="row"{{ $partClass( 'row' ) }}>
                        @foreach ( $row as $day )
                            <div
                                data-scope="calendar"
                                data-part="cell"
                                role="gridcell"
                                id="{{ $id }}-{{ $day['date'] }}"
                                data-date="{{ $day['date'] }}"
                                tabindex="{{ $day['focus'] ? '0' : '-1' }}"
                                @if ( $day['outside'] ) data-outside @endif
                                @if ( $day['today'] ) data-today aria-current="date" @endif
                                wire:key="{{ $id }}-{{ $day['date'] }}"
                                {{ $partClass( 'cell' ) }}
                            >
                                <span class="apui-sr-only">{{ $day['label'] }}</span>
                                @if ( 'month' === $model['view'] )
                                    <span data-scope="calendar" data-part="date" aria-hidden="true"{{ $partClass( 'date' ) }}>{{ $day['day'] }}</span>
                                @endif
                                @foreach ( $day['events'] as $item )
                                    @php
                                        $time           = 'month' === $model['view'] ? $item['time'] : $item['range'];
                                        $eventHrefValue = $eventHref( $item['href'] );
                                        $eventTag       = null !== $eventHrefValue ? 'a' : 'button';
                                    @endphp
                                    <{{ $eventTag }}
                                        @if ( 'a' === $eventTag ) href="{{ $eventHrefValue }}" @else type="button" @endif
                                        data-scope="calendar"
                                        data-part="event"
                                        data-color="{{ $item['color'] }}"
                                        data-event-key="{{ $item['eventKey'] }}"
                                        @if ( 'button' === $eventTag && $details ) aria-haspopup="dialog" @endif
                                        @if ( 'button' === $eventTag ) x-on:click="$dispatch('event-click', {{ $js( $item['event'] ) }})" @endif
                                        wire:key="{{ $id }}-event-{{ $item['key'] }}"
                                        {{ $partClass( 'event' ) }}
                                    >@if ( '' !== $time )<span data-scope="calendar" data-part="event-time"{{ $partClass( 'eventTime' ) }}>{{ $time }}</span>@endif<span data-scope="calendar" data-part="event-title"{{ $partClass( 'eventTitle' ) }}>{{ $item['title'] }}</span><span class="apui-sr-only">, {{ $item['context'] }}</span></{{ $eventTag }}>
                                @endforeach
                                @if ( $day['more'] > 0 )
                                    <button
                                        type="button"
                                        data-scope="calendar"
                                        data-part="more"
                                        aria-label="{{ $day['moreLabel'] }}"
                                        x-on:click="moreFocus({{ $js( $day['date'] ) }}); $dispatch('date-change', {{ $js( $day['date'] ) }}); $dispatch('view-change', 'day')"
                                        {{ $partClass( 'more' ) }}
                                    >{{ $day['moreText'] }}</button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @if ( [] !== $model['legend'] )
        <ul data-scope="calendar" data-part="legend" aria-label="{{ $labels['legend'] }}"{{ $partClass( 'legend' ) }}>
            @foreach ( $model['legend'] as $entry )
                <li data-scope="calendar" data-part="legend-item" data-color="{{ $entry['color'] }}"{{ $partClass( 'legendItem' ) }}>{{ $entry['category'] }}</li>
            @endforeach
        </ul>
    @endif
</div>
