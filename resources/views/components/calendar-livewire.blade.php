{{--
    The Livewire Calendar (#64): the calendar markup with the view and date
    kept here. view-change, date-change and event-click from the grid call
    the component; create and event-click bubble on for a parent. A clicked
    event opens in the design-system Dialog, whose body is the internal
    event-modal-content child (or the component event-view names), keyed by
    the event so it mounts with it.
--}}
<div
    x-on:view-change="$wire.setView( $event.detail )"
    x-on:date-change="$wire.setDate( $event.detail )"
    @if ( $details ) x-on:event-click="$wire.openEvent( $event.target.closest( '[data-event-key]' ).dataset.eventKey )" @endif
>
    @php( $model = $this->model() )
    <x-artisanpack-calendar
        :id="$calendarId"
        :view="$model['view']"
        :date="$model['date']"
        :today="$model['today']"
        :events="$events"
        :week-starts-on="$weekStartsOn"
        :locale="$locale"
        :time-zone="$timeZone"
        :max-events="$maxEvents"
        :creatable="$creatable"
        :details="$details"
        :labels="$labels"
        :classes="$classes"
    />
    @if ( $details )
        <x-artisanpack-dialog
            wire:model="eventModal"
            :id="$calendarId . '-details'"
            :portal="false"
            :title="$selectedEvent['title'] ?? ''"
            :description="$selectedEvent['when'] ?? null"
        >
            @if ( null !== $selectedEvent )
                <livewire:dynamic-component :is="$this->detailsComponent()" :event="$selectedEvent" :key="$calendarId . '-details-' . $selectedKey" />
            @endif
        </x-artisanpack-dialog>
    @endif
</div>
