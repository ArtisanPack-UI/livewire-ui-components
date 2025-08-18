---
title: Calendar Component
---

# Calendar Component

The Calendar component is a flexible, responsive calendar solution that supports multiple views, event display, and color schemes.

## Basic Usage

```php
<x-calendar />
```

## Features

- Multiple views: day, week, month, year
- Event display with color schemes
- Responsive design
- Dark mode support
- Custom color schemes
- Event interaction

## Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| id | string | auto-generated | Unique identifier for the calendar |
| months | int | 1 | Number of months to display (for month view) |
| locale | string | 'en-EN' | Locale for date formatting |
| weekendHighlight | bool | false | Whether to highlight weekends |
| sundayStart | bool | false | Whether to start the week on Sunday (default is Monday) |
| colorScheme | string | 'primary' | Color scheme for the calendar (primary, secondary, accent, custom) |
| customColor | string | null | Custom color for the calendar (hex code, used when colorScheme is 'custom') |
| view | string | 'month' | Calendar view (day, week, month, year) |
| config | array | [] | Additional configuration options |
| events | array | [] | Events to display on the calendar |

## Views

The Calendar component supports four different views:

### Day View

Shows a detailed view of a single day.

```php
<x-calendar view="day" />
```

### Week View

Shows a week-long period.

```php
<x-calendar view="week" />
```

### Month View

Shows a full month (default view).

```php
<x-calendar view="month" />
```

### Year View

Shows a full year.

```php
<x-calendar view="year" />
```

## Color Schemes

The Calendar component supports four different color schemes:

### Primary Color Scheme

```php
<x-calendar color-scheme="primary" />
```

### Secondary Color Scheme

```php
<x-calendar color-scheme="secondary" />
```

### Accent Color Scheme

```php
<x-calendar color-scheme="accent" />
```

### Custom Color Scheme

```php
<x-calendar color-scheme="custom" custom-color="#8A2BE2" />
```

## Events

Events can be displayed on the calendar using the `events` property. Each event should be an array with the following properties:

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| id | string | No | Unique identifier for the event (auto-generated if not provided) |
| date | string | Yes (or range) | Date of the event (YYYY-MM-DD format) |
| range | array | Yes (or date) | Date range of the event ([start_date, end_date]) |
| label | string | No | Short label for the event (displayed on the calendar) |
| title | string | No | Title of the event (displayed in tooltip) |
| start_time | string | No | Start time of the event (displayed in tooltip) |
| colorScheme | string | No | Color scheme for the event (primary, secondary, accent, custom) |
| customColor | string | No | Custom color for the event (hex code, used when colorScheme is 'custom') |

### Example with Events

```php
<x-calendar 
    :events="[
        [
            'id' => 'meeting-1',
            'date' => '2025-07-20',
            'label' => 'Team Meeting',
            'title' => 'Quarterly planning session',
            'start_time' => '10:00',
            'colorScheme' => 'primary',
        ],
        [
            'id' => 'conference-1',
            'range' => ['2025-07-25', '2025-07-27'],
            'label' => 'Conference',
            'title' => 'Annual industry conference',
            'start_time' => '09:00',
            'colorScheme' => 'accent',
        ]
    ]"
/>
```

## Event Handling

The Calendar component dispatches a custom event when an event is clicked. You can listen for this event using Alpine.js:

```php
<div
    x-data="{
        handleEventClick(eventId) {
            // Handle event click
            console.log('Event clicked:', eventId);
        }
    }"
    @calendar-event-click.window="handleEventClick($event.detail.eventId)"
>
    <x-calendar 
        :events="[
            [
                'id' => 'meeting-1',
                'date' => '2025-07-20',
                'label' => 'Team Meeting',
                'title' => 'Quarterly planning session',
                'start_time' => '10:00',
                'colorScheme' => 'primary',
            ]
        ]"
    />
</div>
```

## Responsive Behavior

The Calendar component is fully responsive and adapts to different screen sizes:

- **Large screens**: Full day names, more detailed event information
- **Medium screens**: Abbreviated day names, compact event display
- **Small screens**: Minimal day names, simplified event display

## Dark Mode Support

The Calendar component supports dark mode out of the box. It uses Tailwind's dark mode variants to apply appropriate styling in dark mode.

## Navigation

The Calendar component provides navigation controls to move between time periods:

- **Previous**: Navigate to the previous day, week, month, or year (depending on the current view)
- **Today**: Jump to the current date
- **Next**: Navigate to the next day, week, month, or year (depending on the current view)

## Accessibility

The Calendar component is designed with accessibility in mind:

- Proper color contrast for all color schemes
- Keyboard navigation support
- Screen reader friendly markup
- ARIA attributes for interactive elements

## Examples

### Basic Calendar

```php
<x-calendar />
```

### Calendar with Custom Color

```php
<x-calendar color-scheme="custom" custom-color="#8A2BE2" />
```

### Calendar with Week View and Events

```php
<x-calendar 
    view="week"
    :events="[
        [
            'date' => '2025-07-20',
            'label' => 'Meeting',
            'title' => 'Team meeting',
            'start_time' => '10:00',
            'colorScheme' => 'primary',
        ]
    ]"
/>
```

### Calendar with Event Handling

```php
<div
    x-data="{
        handleEventClick(eventId) {
            // Handle event click
            console.log('Event clicked:', eventId);
        }
    }"
    @calendar-event-click.window="handleEventClick($event.detail.eventId)"
>
    <x-calendar 
        :events="[
            [
                'id' => 'meeting-1',
                'date' => '2025-07-20',
                'label' => 'Team Meeting',
                'title' => 'Quarterly planning session',
                'start_time' => '10:00',
                'colorScheme' => 'primary',
            ]
        ]"
    />
</div>
```