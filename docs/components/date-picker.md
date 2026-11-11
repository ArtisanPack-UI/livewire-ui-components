---
title: DatePicker Component
---

# DatePicker Component

A text field with a calendar popover for a single date or a range, built on the ArtisanPack UI design system. With `time` it is DateTime: date and time in one field. Typing a date always works; the calendar is an aid. DatePicker replaces the flatpickr-based `datepicker` and the native `datetime`, which are now deprecated aliases (see [Deprecated names](#deprecated-names)).

The interaction comes from the design system's behavior engine (`apuiDatePicker`), so load the Alpine adapter before Livewire's script: `@artisanpackScripts` in the layout, which the installer adds (see [Installation](../installation)). flatpickr and the `datepicker-theme.css` files are no longer needed: the component is styled by `@artisanpack-ui/css` through tokens.

## Basic Usage

```blade
<x-artisanpack-date-picker wire:model="moveIn" :label="__( 'Move-in date' )" :hint="__( 'MM/DD/YYYY' )" required />
```

`wire:model` binds the value both ways through `$wire.entangle()`. The value is an ISO 8601 string, `2026-11-12`, whatever the locale shows; use `wire:model.live` to send every change at once. The text follows a numeric pattern from the locale (`MM/dd/yyyy` for `en-US`, `dd.MM.yyyy` for `de`), so show it as the hint. Don't force the calendar for birthdates: people can type.

## Range

```blade
<x-artisanpack-date-picker wire:model="stay" mode="range" :label="__( 'Stay' )" :min="now()->toDateString()" :disabled-dates="$blackoutDates" />
```

The box holds two inputs and the value is an array of two ISO dates, `['2026-11-12', '2026-11-18']`. `min`, `max` and `disabled-dates` strike out what can't be chosen.

## Date and time

```blade
<x-artisanpack-date-picker wire:model="viewingAt" time :step="30" time-zone="Europe/Madrid" :label="__( 'Viewing' )" />
```

`time` adds a time list (a listbox of times every `step` minutes, 24-hour) beside the month, and the value carries the time: `2026-11-12T11:00`. Picking a day keeps the time and the popover open; picking a time closes it. A day without a time is midnight. `time-zone` is shown under the field: show it whenever people in different zones use the data. Time mode is for single dates.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | string or array | none | The ISO value. `wire:model` binds it instead. |
| `mode` | `single` · `range` | `single` | One date, or a range of two. Emitted as `data-mode`. |
| `time` | bool | `false` | Date and time: adds the time list. Emitted as `data-time`. |
| `step` | int | `30` | Minutes between times in the time list. |
| `time-zone` | string | none | The IANA time zone, shown under the field in time mode. |
| `locale` | string | app locale | BCP 47 locale for month and weekday names (from `Intl`), the first day of the week and the default pattern. |
| `min` · `max` | string | none | The earliest and latest dates, ISO. |
| `disabled-dates` | array | `[]` | Dates that can't be chosen, ISO. |
| `week-starts-on` | int | locale | First day of the week, 0 (Sunday) to 6. |
| `format` | string | locale | The text pattern with `yyyy`, `MM` and `dd`, such as `dd.MM.yyyy`. |
| `size` | `sm` · `md` · `lg` | `md` | Control height. Emitted as `data-size`. |
| `label` | string or slot | none | The visible label, linked to the input. |
| `hint` | string or slot | none | Help text, linked with `aria-describedby`. |
| `error` | string | `$errors` | Validation message; defaults to the first message for `error-field` (the `wire:model` name). |
| `invalid` · `disabled` · `readonly` · `required` | bool | `false` | Field states, emitted as `data-invalid`, `data-disabled`, `data-readonly` and `required` on the input. |
| `placeholder` | string | the pattern | Placeholder text, `MM/DD/YYYY` by default. |
| `name` | string | none | Submits the ISO value in a hidden input (`name[]` twice in range mode). |
| `open` | bool | `false` | Whether the calendar starts open. |
| `portal` | bool | `true` | Renders the popover at the end of the body (`x-teleport`). Off renders it in place. |
| `footer` | string or slot | none | A row under the calendar, such as a summary and an Apply button. |
| `trigger-label` | string | `__( 'Choose date' )` | The calendar button's accessible name (`__( 'Choose date and time' )` in time mode). |
| `previous-label` · `next-label` | string | `__( 'Previous month' )` · `__( 'Next month' )` | The month buttons' names. |
| `calendar-label` | string | `__( 'Calendar' )` | The popover's name. |
| `time-label` | string | `__( 'Time' )` | The time list's name and heading. |
| `end-label` | string | `__( 'End date' )` | The end input's name in range mode. |
| `time-zone-label` | string | `__( 'Time zone: :zone' )` | The time-zone line; `{zone}` stands for the zone in your own text. |
| `error-field` | string | `wire:model` | The `$errors` key. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `box`, `trigger`, `control`, `hint`, `timeZone`, `error`, `positioner`, `content`, `view`, `viewControl`, `prevTrigger`, `rangeText`, `nextTrigger`, `table`, `tableHead`, `tableRow`, `tableHeader`, `tableBody`, `tableCell`, `tableCellTrigger`, `time`, `timeLabel`, `timeList`, `timeOption`, `footer`. |

Every built-in label goes through `__()`, so add them to your `lang/*.json` to translate them. Month and weekday names come from the browser's `Intl` in `locale`.

## Attributes and events

`class` lands on the root (`.apui-date-picker`). Event listeners (`x-on:*`, `wire:*` other than `wire:model`) land on the root too, which is where the widget dispatches `value-change` (`$event.detail.value`) and `open-change` (`$event.detail.open`) when it changes them itself. Every other attribute (`autocomplete`, `aria-*`, `data-*`) lands on the text input.

```blade
<x-artisanpack-date-picker :label="__( 'Check-in' )" x-on:value-change="nights = recount( $event.detail.value )" />
```

## Keyboard

| Keys | Action |
|---|---|
| Tab | Moves between the calendar button and the input; typing a date in the pattern always works. |
| Enter, Space | On the calendar button, opens it; on a day, chooses it. |
| ArrowLeft, ArrowRight | Previous and next day. They follow the text direction, so they swap under RTL. |
| ArrowUp, ArrowDown | Same day of the previous and next week; in the time list, the previous and next time. |
| PageUp, PageDown | Previous and next month (Shift for a year). |
| Home, End | Start and end of the week; in the time list, the first and last time. |
| Escape | Closes the calendar. |

## Ids and morphing

The field's parts have stable, server-rendered ids, so Livewire's morph keeps them wired. The base id is the `id` attribute, else one derived from the `wire:model` name. The month is rendered on the client from the machine's state, inside the `wire:ignore` positioner, so a server re-render never touches it.

## Markup

```html
<div class="apui-date-picker" data-scope="date-picker" data-part="root" data-size="md" data-mode="single" x-data="apuiDatePicker({ … })">
	<label data-part="label">Move-in date</label>
	<div data-part="box">
		<button data-part="trigger" aria-label="Choose date">…calendar…</button>
		<input data-part="control" />
	</div>
	<p data-part="hint">MM/DD/YYYY</p>
	<div data-part="positioner" wire:ignore>
		<div data-part="content">
			<div data-part="view">
				<div data-part="view-control">prev-trigger · range-text · next-trigger</div>
				<table data-part="table" role="grid">table-head · table-body → table-row → table-cell → table-cell-trigger</table>
			</div>
			<div data-part="time">time-label · time-list (role=listbox) → time-option</div>
		</div>
	</div>
</div>
```

Every part carries `data-scope="date-picker"`. The machine's own `control` and `input` parts are renamed `box` and `control`, the field anatomy every form component shares.

## Deprecated names

- `<x-artisanpack-datepicker>` is an alias of `<x-artisanpack-date-picker>` and takes its props. A 2.x flatpickr `config` is mapped where an equivalent exists: `mode: 'range'` → `mode="range"`, `minDate` and `maxDate` (an ISO date or `'today'`) → `min` and `max`, `dateFormat` (its `Y`, `m` and `d`) → `format`, `enableTime` → `time`. The rest of `config` is ignored.
- `<x-artisanpack-datetime>` follows its 2.x `type`: `date` (the 2.x default) is a date; `datetime-local` is time mode (`2026-11-12T11:00`). `type="time"` also renders time mode, since a time here always belongs to a date; for a time on its own, use `<x-artisanpack-input type="time">`.
- The 2.x props `icon`, `icon-right`, `prefix`, `suffix`, `inline`, `clearable`, `hint-class`, `omit-error`, `first-error-only`, `error-class`, `color`, `color-adjustment`, `theme`, `font-config` and `dark-mode-support` are accepted and ignored, and never reach the input. The `prepend` and `append` slots are gone: put related controls next to the field instead.

Both aliases are removed in a later major release.
