# Calendar Component

The Calendar component provides a flexible and customizable calendar display for your application. It features a responsive design with support for different views (day, week, month, year), color schemes, event display, and localization.

## Basic Usage

```php
<x-calendar />
```

## Examples

### Basic Calendar

```php
<x-calendar />
```

### Calendar with Primary Color Scheme

```php
<x-calendar color-scheme="primary" />
```

### Calendar with Secondary Color Scheme

```php
<x-calendar color-scheme="secondary" />
```

### Calendar with Accent Color Scheme

```php
<x-calendar color-scheme="accent" />
```

### Calendar with Custom Color

```php
<x-calendar color-scheme="custom" custom-color="#8A2BE2" />
```

### Different Calendar Views

```php
<!-- Day View -->
<x-calendar view="day" />

<!-- Week View -->
<x-calendar view="week" />

<!-- Month View (default) -->
<x-calendar view="month" />

<!-- Year View -->
<x-calendar view="year" />
```

### Multiple Month Calendar

```php
<x-calendar :months="3" />
```

### Calendar with Weekend Highlighting

```php
<x-calendar :weekendHighlight="true" />
```

### Calendar with Sunday as First Day of Week

```php
<x-calendar :sundayStart="true" />
```

### Calendar with Custom Locale

```php
<x-calendar locale="fr-FR" />
```

### Calendar with Events

```php
@php
$events = [
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
    ],
    [
        'id' => 'custom-event',
        'date' => '2025-07-15',
        'label' => 'Custom Event',
        'title' => 'Event with custom color',
        'colorScheme' => 'custom',
        'customColor' => '#FF5733',
    ]
];
@endphp

<x-calendar :events="$events" />
```

### Calendar with Custom Configuration

```php
@php
$config = [
    'settings' => [
        'selected' => [
            'dates' => ['2025-07-10']
        ],
        'visibility' => [
            'theme' => 'light'
        ]
    ]
];
@endphp

<x-calendar :config="$config" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the calendar element |
| `months` | int\|null | `1` | Number of months to display |
| `locale` | string\|null | `'en-EN'` | Locale for the calendar |
| `weekendHighlight` | boolean\|null | `false` | Whether to highlight weekends |
| `sundayStart` | boolean\|null | `false` | Whether the week starts on Sunday |
| `colorScheme` | string\|null | `'primary'` | Color scheme for the calendar (primary, secondary, accent, custom) |
| `customColor` | string\|null | `null` | Hex color code for custom color scheme |
| `view` | string\|null | `'month'` | Calendar view (day, week, month, year) |
| `config` | array\|null | `[]` | Additional configuration options |
| `events` | array\|null | `[]` | Array of events to display on the calendar |

## Event Format

Events can be specified in two formats:

### Single Date Event

```php
[
    'id' => 'event-1',                  // Unique identifier for the event
    'date' => '2025-07-15',             // Date in Y-m-d format
    'label' => 'Meeting',               // Event label (displayed on calendar)
    'title' => 'Team planning meeting', // Event title (displayed in popup)
    'start_time' => '14:00',            // Optional start time
    'colorScheme' => 'primary',         // Color scheme (primary, secondary, accent, custom)
    'customColor' => '#FF5733',         // Custom color (when colorScheme is 'custom')
    'css' => 'custom-class'             // Optional additional CSS classes
]
```

### Date Range Event

```php
[
    'id' => 'event-2',                  // Unique identifier for the event
    'range' => ['2025-07-20', '2025-07-25'], // Start and end dates
    'label' => 'Conference',            // Event label (displayed on calendar)
    'title' => 'Annual conference',     // Event title (displayed in popup)
    'start_time' => '09:00',            // Optional start time
    'colorScheme' => 'accent',          // Color scheme (primary, secondary, accent, custom)
    'customColor' => null,              // Custom color (when colorScheme is 'custom')
    'css' => 'custom-class'             // Optional additional CSS classes
]
```

## Color Schemes

The Calendar component supports four color schemes:

1. **Primary** (`color-scheme="primary"`) - Uses your application's primary color
2. **Secondary** (`color-scheme="secondary"`) - Uses your application's secondary color
3. **Accent** (`color-scheme="accent"`) - Uses your application's accent color
4. **Custom** (`color-scheme="custom" custom-color="#HEX"`) - Uses a custom color specified by the `custom-color` prop

Each event can also have its own color scheme by specifying the `colorScheme` property in the event data.

## Calendar Views

The Calendar component supports four different views:

1. **Day** (`view="day"`) - Shows a detailed view of a single day
2. **Week** (`view="week"`) - Shows a week view with days as columns
3. **Month** (`view="month"`) - Shows a traditional month calendar (default)
4. **Year** (`view="year"`) - Shows a year overview with months

Users can switch between views using the view selector in the calendar header.

## Event Interactions

Events on the calendar are interactive:

- Hovering over an event shows a subtle scale animation
- Clicking on an event displays a popup with detailed information
- Events with the same color scheme are visually grouped

## Responsive Behavior

The calendar adapts to different screen sizes:

- **Large screens**: Full day names, more detailed event information
- **Medium screens**: Short day names (e.g., "Mon" instead of "Monday")
- **Small screens**: Minimal day names (e.g., "M" instead of "Monday"), compact layout

## Styling

The Calendar component uses Tailwind CSS for styling. You can customize the appearance by:

1. Using the provided props (`colorScheme`, `customColor`, etc.)
2. Adding custom CSS classes via the `css` property in events
3. Providing custom configuration via the `config` prop

### Default Classes

- Calendar container: `mx-auto px-4 lg:container`
- Calendar header: `flex justify-between items-center p-4 border-b border-stroke dark:border-dark-3`
- Weekdays: `grid grid-cols-7 text-center py-2 border-b border-stroke dark:border-dark-3`
- Month grid: `grid grid-cols-7 gap-px`
- Day cell: `p-1 h-24 sm:h-28 md:h-32 border border-stroke dark:border-dark-3`
- Event: `rounded-md p-1 text-xs mb-1 truncate shadow-sm`

## Methods

The Calendar component includes several helper methods:

| Method | Description |
|--------|-------------|
| `getContrastColor(string $hexColor)` | Determines appropriate text color (black or white) based on background color |
| `addCss(string $config)` | Adds Tailwind CSS classes to the calendar configuration |
| `popups()` | Generates HTML for event popups |
| `customColorScript()` | Generates JavaScript for handling custom colors |
| `setup()` | Sets up the calendar configuration |

## Accessibility

The Calendar component follows accessibility best practices:

- Uses semantic HTML for calendar structure
- Provides proper labeling for dates and events
- Supports keyboard navigation
- Includes appropriate ARIA attributes
- Ensures sufficient color contrast for all color schemes

## Related Components

- [DatePicker](datepicker.md) - Date selection input
- [DateTime](datetime.md) - Date and time selection input
