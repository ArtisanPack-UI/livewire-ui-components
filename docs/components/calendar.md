# Calendar Component

The Calendar component provides a flexible and customizable calendar display for your application. It's built on top of the VanillaCalendar JavaScript library and supports various features like multiple month views, event display, and localization.

## Basic Usage

```php
<x-artisanpack-calendar />
```

## Examples

### Single Month Calendar

```php
<x-artisanpack-calendar />
```

### Multiple Month Calendar

```php
<x-artisanpack-calendar :months="3" />
```

### Calendar with Weekend Highlighting

```php
<x-artisanpack-calendar :weekendHighlight="true" />
```

### Calendar with Sunday as First Day of Week

```php
<x-artisanpack-calendar :sundayStart="true" />
```

### Calendar with Custom Locale

```php
<x-artisanpack-calendar locale="fr-FR" />
```

### Calendar with Events

```php
@php
$events = [
    [
        'date' => '2023-10-15',
        'label' => 'Meeting',
        'description' => 'Team meeting at 2 PM',
        'css' => 'bg-primary text-white'
    ],
    [
        'range' => ['2023-10-20', '2023-10-25'],
        'label' => 'Conference',
        'description' => 'Annual industry conference',
        'css' => 'bg-secondary text-white'
    ]
];
@endphp

<x-artisanpack-calendar :events="$events" />
```

### Calendar with Custom Configuration

```php
@php
$config = [
    'settings' => [
        'selected' => [
            'dates' => ['2023-10-10']
        ],
        'visibility' => [
            'theme' => 'light'
        ]
    ]
];
@endphp

<x-artisanpack-calendar :config="$config" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the calendar element |
| `months` | int\|null | `1` | Number of months to display |
| `locale` | string\|null | `'en-EN'` | Locale for the calendar |
| `weekendHighlight` | boolean\|null | `false` | Whether to highlight weekends |
| `sundayStart` | boolean\|null | `false` | Whether the week starts on Sunday |
| `config` | array\|null | `[]` | Additional configuration options for VanillaCalendar |
| `events` | array\|null | `[]` | Array of events to display on the calendar |

## Event Format

Events can be specified in two formats:

### Single Date Event

```php
[
    'date' => '2023-10-15', // Date in Y-m-d format
    'label' => 'Meeting', // Event title
    'description' => 'Team meeting at 2 PM', // Optional description
    'css' => 'bg-primary text-white' // CSS classes for styling
]
```

### Date Range Event

```php
[
    'range' => ['2023-10-20', '2023-10-25'], // Start and end dates
    'label' => 'Conference', // Event title
    'description' => 'Annual industry conference', // Optional description
    'css' => 'bg-secondary text-white' // CSS classes for styling
]
```

## Styling

The Calendar component uses VanillaCalendar with custom styling to match your application's theme. You can customize the appearance by:

1. Using the provided props (`weekendHighlight`, etc.)
2. Adding custom CSS classes via the `css` property in events
3. Providing custom configuration via the `config` prop

### Default Classes

- `vanilla-calendar` - Base calendar class
- `vanilla-calendar-grid` - Grid container for the calendar
- `flex flex-wrap justify-around` - Layout utilities for responsive design

## Accessibility

The Calendar component follows accessibility best practices:

- Uses semantic HTML for calendar structure
- Provides proper labeling for dates and events
- Supports keyboard navigation
- Includes appropriate ARIA attributes

## Related Components

- [DatePicker](datepicker.md) - Date selection input
- [DateTime](datetime.md) - Date and time selection input