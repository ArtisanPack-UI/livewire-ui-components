---
title: Calendar
menu_order: 3
---

# Calendar

[Calendar](https://docs.artisanpackui.dev/documentation/design-system/components/calendar) shows events on a month grid, a week, a day or an agenda list. The component page covers its props, events and accessibility. Livewire UI Components renders it in two forms:

| Tag | What it is |
|---|---|
| `<livewire:artisanpack-calendar>` | A Livewire component that keeps the view and the date itself, re-renders on Today, previous, next, "+N more" and the view tabs, and opens a clicked event in a Dialog |
| `<x-artisanpack-calendar>` | The same markup with no state of its own. Its controls dispatch browser events for your component to answer |

The calendar is drawn on the server. Alpine only adds the arrow keys.

## The Livewire component

```blade
<livewire:artisanpack-calendar
    :events="$events"
    :week-starts-on="1"
    time-zone="America/Chicago"
/>
```

It takes the Calendar props (`view`, `date`, `today`, `events`, `week-starts-on`, `locale`, `time-zone`, `max-events`, `creatable`, `details`, `labels`, `classes` and `id`), plus `event-view`.

Its actions are `setView( $view )`, `setDate( $date )` and `openEvent( $key )`. The 2.x actions `goToNextPeriod()`, `goToPreviousPeriod()`, `goToToday()` and `loadEventModal( $id )` still work.

Localized month and day names need PHP's `intl` extension. Without it, they're in English.

### Event details

Clicking an event opens a Dialog with its title, when it is, its category and its description. To show something else, name your own Livewire component in `event-view`:

```blade
<livewire:artisanpack-calendar :events="$events" event-view="booking-details" />
```

It's mounted with the event, with `when`, `category`, `color` and `description` filled in:

```php
class BookingDetails extends Component
{
    public array $event = [];

    public function mount( array $event ): void
    {
        $this->event = $event;
    }
}
```

The calendar also dispatches the 2.x `loadEventModal` Livewire event, with `selectedEvent` and the sending calendar's `calendarId`, for components that listen for it.

Set `:details="false"` to turn the Dialog off. Clicks still dispatch `event-click`.

## With your own state

Use the Blade tag inside your own Livewire component when you want to own the view, the date and what a click does. It dispatches `view-change`, `date-change`, `event-click` and `create`:

```blade
<x-artisanpack-calendar
    :events="$events"
    :view="$view"
    :date="$date"
    creatable
    :details="false"
    x-on:view-change="$wire.set( 'view', $event.detail )"
    x-on:date-change="$wire.set( 'date', $event.detail )"
    x-on:event-click="$wire.editEvent( $event.detail.id )"
    x-on:create="$wire.createEvent( $event.detail )"
/>
```

`$event.detail` is the view, the `YYYY-MM-DD` date, the event as you passed it in, or the date shown when New event was clicked. Set `:details="false"` unless you open a dialog on `event-click`: with `details` on, events announce that they open one.

## Upgrading from 2.x

- `<livewire:artisanpack-calendar>` no longer loads lazily. Add `lazy` to defer it.
- `sunday-start` still works: `true` is `:week-starts-on="0"`, `false` is `1`.
- `view="year"` falls back to `month`. `months`, `color-scheme`, `custom-color`, `weekend-highlight` and `config` are ignored: tint events with their `color`.
- 2.x event arrays still work: `date` as `start`, `range` (`[start, end]`), `time` (`[start, end]` times on the date), `label` as `title` and `colorScheme` as `color`. `customColor` is ignored.
- A custom `event-view` component now receives the event in `mount()`.
