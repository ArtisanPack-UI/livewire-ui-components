---
title: Calendar Component
---

# Calendar Component

Events on a month grid, a week, a day or an agenda list, built on the ArtisanPack UI design system (`.apui-calendar`). The toolbar has Today, previous and next, the title, view tabs and an optional New event button. The days, titles, labels and event order come from `Support\CalendarModel`, a PHP port of the date rules React and Vue use, so all three stacks render the same calendar. It's drawn on the server; Alpine only adds the arrow keys.

There are two tags:

- `<livewire:artisanpack-calendar>` is a full Livewire component. It keeps the view and date, re-renders on Today, previous, next, "+N more" and the view tabs, and opens a clicked event in a [Dialog](dialog).
- `<x-artisanpack-calendar>` renders the same markup with no state of its own. Its controls dispatch browser events for your page or component to answer.

## Basic Usage

```blade
<livewire:artisanpack-calendar
    :events="[
        [ 'id' => 1, 'start' => '2026-11-02T09:00', 'end' => '2026-11-02T10:00', 'title' => __( 'Team meeting' ), 'color' => 'error', 'category' => __( 'Marketing' ) ],
        [ 'id' => 2, 'start' => '2026-11-05', 'title' => __( 'Anna’s birthday' ), 'color' => 'accent', 'category' => __( 'People' ) ],
        [ 'id' => 3, 'start' => '2026-11-12T11:00', 'title' => __( 'Viewing · Lakeview' ), 'color' => 'primary', 'category' => __( 'Viewings' ), 'description' => __( 'Meet the buyers at the lobby.' ) ],
    ]"
    :week-starts-on="1"
/>
```

## Events

Each event is an array (or an object with `toArray()`):

| Key | Description |
|---|---|
| `id` | A stable id. The list index when unset. |
| `start` | `2026-11-24` for an all-day event, `2026-11-02T09:00` for a timed one. A `Z` or an offset (or a `DateTime`/Carbon) is converted to `time-zone`. |
| `end` | Optional. Inclusive for dates; a timed event ending at `00:00` ends the day before. Multi-day events show on every day. |
| `title` | The text on the event. |
| `color` | A soft tint: `primary` (default), `secondary`, `accent`, `success`, `warning`, `error`, `info` or `neutral`. Tint by category. |
| `allDay` | All day even when `start` has a time. |
| `category` | Shown in the legend under the calendar and in the event's accessible name. |
| `description` | Shown in the details Dialog. |
| `href` | Makes the event a link instead of a button. |

Events on a day sort all-day first, then by start time, then as given. A month cell shows `max-events` (2) and folds the rest into a "+N more" button that opens that day.

## Views

`view` is `month` (default), `week`, `day` or `agenda`. Previous and next move by a month (agenda too), a week or a day, keeping the day of the month where it exists. The agenda lists the month's days that have events, each with its date as a heading: it's the accessible list alternative to the grid.

## With your own state

`<x-artisanpack-calendar>` dispatches `view-change`, `date-change`, `event-click` and `create` (`$event.detail` holds the view, the `YYYY-MM-DD` date, the event as passed in, and the shown date). Inside your own Livewire component:

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

Set `:details="false"` on the x-tag unless you open a dialog on `event-click`: with `details`, events announce that they open one (`aria-haspopup="dialog"`). The Livewire component opens it for you; `create` and `event-click` still bubble past it, so a parent can listen with `x-on:create`.

## Event details

The Livewire component opens a clicked event in a Dialog: its title, when it is, then its category as a soft Badge and its description. The body is an internal child component; `event-view` names another Livewire component to use instead. It's mounted with the event (`mount( array $event )`), with `when`, `category`, `color` and `description` filled in, and the 2.x `loadEventModal` event is still dispatched.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `view` | string | `month` | `month`, `week`, `day` or `agenda`. |
| `date` | string | today | The date the view shows, `YYYY-MM-DD`. |
| `today` | string | the current date in `time-zone` | The date marked as today. |
| `events` | array | `[]` | The events. |
| `week-starts-on` | int | the locale's | The first day of the week, 0 (Sunday) to 6. |
| `locale` | string | the app locale | BCP 47 locale for names and the first day of the week. Times show as 24-hour `HH:mm`. |
| `time-zone` | string | `UTC` | For today and zoned times. |
| `max-events` | int | `2` | Events a month cell shows before "+N more". |
| `creatable` | bool | `false` | Shows the New event button (`create`). |
| `details` | bool | `true` | Events open a details Dialog (the Livewire component) and carry `aria-haspopup="dialog"`. |
| `labels` | array | | Built-in text: `today`, `previous`, `next`, `views`, `month`, `week`, `day`, `agenda`, `allDay`, `more` (`{count}`), `showMore` (`{count}`, `{date}`), `newEvent`, `empty`, `legend`. Defaults go through `__()`. |
| `classes` | array | `[]` | Extra classes per part: `root`, `toolbar`, `todayTrigger`, `previousTrigger`, `nextTrigger`, `title`, `viewTabs`, `createTrigger`, `body`, `grid`, `row`, `weekday`, `cell`, `date`, `event`, `eventTime`, `eventTitle`, `more`, `legend`, `legendItem`. |
| `event-view` | string | `event-modal-content` | Livewire component only: the details Dialog's body. |
| `id` | string | generated | The root's id; parts are named after it. |

## Upgrading from 2.x

- The calendar is rebuilt on the design system: the DaisyUI and Tailwind classes, the custom color script and the islands are gone.
- `<livewire:artisanpack-calendar>` no longer loads lazily: pass `lazy` to defer it. Its placeholder view is removed.
- `sunday-start` still works: `true` is `week-starts-on="0"`, `false` is `1`. Without either, the week follows the locale.
- `view="year"` falls back to `month`. `months`, `color-scheme`, `custom-color`, `weekend-highlight` and `config` are ignored: tint events with `color`.
- 2.x events still work: `date` (start), `range` (`[start, end]`), `time` (`[start, end]` times on the date), `label` (title) and `colorScheme` (color). `customColor` is ignored.
- `goToNextPeriod()`, `goToPreviousPeriod()`, `goToToday()` and `loadEventModal( $id )` still work; the new actions are `setView()`, `setDate()` and `openEvent()`.
- A custom `event-view` component now receives the event in `mount()`; `loadEventModal` is still dispatched for components that listen for it.

## Accessibility

- The month, week and day are `role="grid"` named by the title, with rows, column headers and gridcells. The grid is one tab stop: the arrow keys move between days (left and right follow the text direction), up and down by a week, Home and End to the row's ends.
- Each day is named by its full localized date; today has `aria-current="date"`.
- Each event is a button or link named by its title, category and full date and time, never by its tint alone. The legend keeps the category key visible.
- The view tabs are a tablist controlling the calendar body; the arrow keys move between them and Enter or Space chooses one. The title is a polite live region, so moving through dates is announced.
- The agenda view is the accessible list alternative to the grid.
- The previous and next chevrons mirror in right-to-left layouts.
