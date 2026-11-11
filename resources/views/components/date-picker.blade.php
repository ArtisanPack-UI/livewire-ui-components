{{--
    DatePicker (#55), and DateTime as its time mode. Markup contract:
    design-system DatePicker. The field's parts are server-rendered with
    stable ids (D-015); the month is client-rendered from `weeks` and
    `weekDays` inside the wire:ignore positioner, so the morph leaves it to
    the machine. `class` lands on the root, listeners (x-on:*, wire:*) on the
    root where the widget dispatches, every other attribute on the input.
--}}
@php
    $controlId   = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $isRange     = $isRange();
    $hasTime     = $hasTime();
    $state       = $open ? 'open' : 'closed';
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $zoneText    = $timeZoneText();
    $hasFooter   = $filled( $footer );
    $submitName  = $submitName();
    $calendar    = $calendar();
    $placeholder = $placeholderText();
    $chosenTime  = $hasTime ? $selectedTime() : null;
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $controlId . '-hint' : null,
        null !== $zoneText ? $controlId . '-time-zone' : null,
        null !== $message ? $controlId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-date-picker' ) ) }}
    {{ $listenerAttributes() }}
    data-scope="date-picker"
    data-part="root"
    id="{{ $partId( 'root' ) }}"
    data-size="{{ $size }}"
    data-mode="{{ $isRange ? 'range' : 'single' }}"
    data-state="{{ $state }}"
    @if ( $hasTime ) data-time @endif
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    @if ( $readonly ) data-readonly @endif
    x-data="{{ $alpineData( $isInvalid ) }}"
    x-apui:root
>
    @if ( $hasLabel )
        <label data-scope="date-picker" data-part="label" id="{{ $partId( 'label' ) }}" for="{{ $controlId }}"{{ $partClass( 'label' ) }} x-apui:label>{{ $content( $label ) }}@if ( $required )<span data-scope="date-picker" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</label>
    @endif
    <div data-scope="date-picker" data-part="box" id="{{ $partId( 'box' ) }}"{{ $partClass( 'box' ) }} x-apui:box>
        <button
            type="button"
            data-scope="date-picker"
            data-part="trigger"
            id="{{ $partId( 'trigger' ) }}"
            aria-label="{{ $triggerName() }}"
            aria-haspopup="grid"
            aria-expanded="{{ $open ? 'true' : 'false' }}"
            aria-controls="{{ $partId( 'content' ) }}"
            @if ( $disabled ) disabled @endif
            {{ $partClass( 'trigger' ) }}
            x-apui:trigger
        >{{ $iconSvg( $hasTime ? 'calendar-days' : 'calendar' ) }}</button>
        <input {{ $controlAttributes() }}
            type="text"
            data-scope="date-picker"
            data-part="control"
            id="{{ $controlId }}"
            autocomplete="off"{{ $partClass( 'control' ) }}
            placeholder="{{ $placeholder }}"
            value="{{ $inputText( 0 ) }}"
            @if ( $disabled ) disabled @endif
            @if ( $readonly ) readonly @endif
            @if ( $required ) required @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
            x-apui:control="{ index: 0 }"
        />
        @if ( $isRange )
            <span aria-hidden="true">–</span>
            <input
                type="text"
                data-scope="date-picker"
                data-part="control"
                id="{{ $controlId }}-1"
                autocomplete="off"
                aria-label="{{ $endInputLabel() }}"{{ $partClass( 'control' ) }}
                placeholder="{{ $placeholder }}"
                value="{{ $inputText( 1 ) }}"
                @if ( $disabled ) disabled @endif
                @if ( $readonly ) readonly @endif
                @if ( $required ) required @endif
                @if ( $isInvalid ) aria-invalid="true" @endif
                @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
                x-apui:control="{ index: 1 }"
            />
        @endif
    </div>
    @if ( null !== $submitName )
        @if ( $isRange )
            <input type="hidden" name="{{ $submitName }}[]" x-bind:value="(value ?? [])[0] ?? ''" />
            <input type="hidden" name="{{ $submitName }}[]" x-bind:value="(value ?? [])[1] ?? ''" />
        @else
            <input type="hidden" name="{{ $submitName }}" x-bind:value="value ?? ''" />
        @endif
    @endif
    @if ( $hasHint )
        <p data-scope="date-picker" data-part="hint" id="{{ $controlId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $zoneText )
        <p data-scope="date-picker" data-part="time-zone" id="{{ $controlId }}-time-zone"{{ $partClass( 'timeZone' ) }}>{{ $zoneText }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="date-picker" data-part="error" id="{{ $controlId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
    @if ( $portal )<template x-teleport="body">@endif
    <div data-scope="date-picker" data-part="positioner" id="{{ $partId( 'positioner' ) }}"{{ $partClass( 'positioner' ) }} wire:ignore x-apui:positioner>
        <div
            data-scope="date-picker"
            data-part="content"
            id="{{ $partId( 'content' ) }}"
            role="application"
            tabindex="-1"
            aria-label="{{ $builtInName( 'calendarLabel', 'Calendar' ) }}"
            data-state="{{ $state }}"
            @if ( $hasTime ) data-time @endif
            @if ( ! $open ) hidden @endif
            {{ $partClass( 'content' ) }}
            x-apui:content
        >
            <div data-scope="date-picker" data-part="view" data-view="day"{{ $partClass( 'view' ) }} x-apui:view>
                <div data-scope="date-picker" data-part="view-control"{{ $partClass( 'viewControl' ) }} x-apui:view-control>
                    <x-artisanpack-button
                        type="button"
                        variant="ghost"
                        color="neutral"
                        size="sm"
                        icon-only
                        leading-icon="chevron-left"
                        :disabled="$disabled"
                        :aria-label="$builtInName( 'previousLabel', 'Previous month' )"
                        :id="$partId( 'prev-trigger' )"
                        :class="$classes['prevTrigger'] ?? null"
                        data-scope="date-picker"
                        data-part="prev-trigger"
                        x-apui:prev-trigger=""
                    />
                    <span data-scope="date-picker" data-part="range-text"{{ $partClass( 'rangeText' ) }} x-apui:range-text x-text="monthLabel">{{ $calendar['label'] }}</span>
                    <x-artisanpack-button
                        type="button"
                        variant="ghost"
                        color="neutral"
                        size="sm"
                        icon-only
                        leading-icon="chevron-right"
                        :disabled="$disabled"
                        :aria-label="$builtInName( 'nextLabel', 'Next month' )"
                        :id="$partId( 'next-trigger' )"
                        :class="$classes['nextTrigger'] ?? null"
                        data-scope="date-picker"
                        data-part="next-trigger"
                        x-apui:next-trigger=""
                    />
                </div>
                <table data-scope="date-picker" data-part="table" id="{{ $partId( 'table' ) }}" role="grid"{{ $partClass( 'table' ) }} x-apui:table>
                    <thead data-scope="date-picker" data-part="table-head" aria-hidden="true"{{ $partClass( 'tableHead' ) }} x-apui:table-head>
                        <tr data-scope="date-picker" data-part="table-row"{{ $partClass( 'tableRow' ) }} x-apui:table-row>
                            <template x-for="(weekDay, w) in weekDays" :key="w">
                                <th data-scope="date-picker" data-part="table-header" scope="col"{{ $partClass( 'tableHeader' ) }} x-apui:table-header x-bind:abbr="weekDay.long" x-text="weekDay.short"></th>
                            </template>
                            {{-- The server's month, until Alpine renders its own (apuiDatePicker removes data-apui-ssr). --}}
                            @foreach ( $calendar['weekDays'] as $weekDay )
                                <th data-scope="date-picker" data-part="table-header" scope="col" abbr="{{ $weekDay['long'] }}" data-view="day"{{ $partClass( 'tableHeader' ) }} data-apui-ssr>{{ $weekDay['short'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody data-scope="date-picker" data-part="table-body"{{ $partClass( 'tableBody' ) }} x-apui:table-body>
                        <template x-for="(week, r) in weeks" :key="r">
                            <tr data-scope="date-picker" data-part="table-row"{{ $partClass( 'tableRow' ) }} x-apui:table-row>
                                <template x-for="day in week" :key="day.iso">
                                    <td data-scope="date-picker" data-part="table-cell" role="gridcell"{{ $partClass( 'tableCell' ) }} x-apui:table-cell="{ value: day.iso }">
                                        <div data-scope="date-picker" data-part="table-cell-trigger" role="button"{{ $partClass( 'tableCellTrigger' ) }} x-apui:table-cell-trigger="{ value: day.iso }" x-text="day.day"></div>
                                    </td>
                                </template>
                            </tr>
                        </template>
                        @foreach ( $calendar['weeks'] as $week )
                            <tr data-scope="date-picker" data-part="table-row" data-view="day"{{ $partClass( 'tableRow' ) }} data-apui-ssr>
                                @foreach ( $week as $day )
                                    <td data-scope="date-picker" data-part="table-cell" role="gridcell" aria-selected="{{ $day['selected'] || $day['inRange'] ? 'true' : 'false' }}" @if ( $day['disabled'] || $day['unavailable'] ) aria-disabled="true" @endif data-value="{{ $day['iso'] }}"{{ $partClass( 'tableCell' ) }}>
                                        <div
                                            data-scope="date-picker"
                                            data-part="table-cell-trigger"
                                            role="button"
                                            tabindex="{{ $day['focused'] && ! $disabled ? '0' : '-1' }}"
                                            aria-label="{{ $day['label'] }}"
                                            data-value="{{ $day['iso'] }}"
                                            data-view="day"
                                            @if ( $day['disabled'] || $day['unavailable'] ) aria-disabled="true" data-disabled @else data-selectable @endif
                                            @if ( $day['selected'] ) data-selected @endif
                                            @if ( $day['inRange'] ) data-in-range @endif
                                            @if ( $day['outside'] ) data-outside-range @endif
                                            @if ( $day['unavailable'] ) data-unavailable @endif
                                            @if ( $day['today'] ) data-today @endif
                                            @if ( $day['focused'] ) data-focus @endif
                                            {{ $partClass( 'tableCellTrigger' ) }}
                                        >{{ $day['day'] }}</div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ( $hasTime )
                <div data-scope="date-picker" data-part="time"{{ $partClass( 'time' ) }}>
                    <span data-scope="date-picker" data-part="time-label" aria-hidden="true"{{ $partClass( 'timeLabel' ) }}>{{ $timeListLabel() }}</span>
                    <div data-scope="date-picker" data-part="time-list" id="{{ $partId( 'time-list' ) }}" role="listbox" aria-label="{{ $timeListLabel() }}"{{ $partClass( 'timeList' ) }} x-apui:time-list>
                        @foreach ( $timeOptions() as $timeOption )
                            <div
                                data-scope="date-picker"
                                data-part="time-option"
                                id="{{ $partId( 'time-' . str_replace( ':', '', $timeOption ) ) }}"
                                role="option"
                                aria-selected="{{ $chosenTime === $timeOption ? 'true' : 'false' }}"
                                @if ( $chosenTime === $timeOption ) data-selected @endif
                                tabindex="{{ $chosenTime === $timeOption || ( null === $chosenTime && $loop->first ) ? '0' : '-1' }}"
                                data-value="{{ $timeOption }}"{{ $partClass( 'timeOption' ) }}
                                x-apui:time-option="{ value: @js( $timeOption ) }"
                            >{{ $timeOption }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if ( $hasFooter )
                <div data-scope="date-picker" data-part="footer"{{ $partClass( 'footer' ) }}>{{ $content( $footer ) }}</div>
            @endif
        </div>
    </div>
    @if ( $portal )</template>@endif
</div>
