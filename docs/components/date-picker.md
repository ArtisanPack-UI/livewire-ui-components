---
title: DatePicker Component
---

# DatePicker Component

The DatePicker component provides an interactive date selection interface with calendar popup and keyboard input support.

## Basic Usage

```php
<x-artisanpack-datepicker
    label="Select Date"
    wire:model="selectedDate"
/>
```

## Examples

### Date Range Selection

```php
<x-artisanpack-datepicker
    label="Start Date"
    wire:model="startDate"
/>

<x-artisanpack-datepicker
    label="End Date"
    wire:model="endDate"
/>
```

### With Icons

```php
<x-artisanpack-datepicker
    label="Birthday"
    icon="o-cake"
    wire:model="birthday"
/>

<x-artisanpack-datepicker
    label="Appointment"
    icon-right="o-calendar"
    wire:model="appointmentDate"
/>
```

### Inline Calendar

```php
<x-artisanpack-datepicker
    label="Pick a Date"
    wire:model="date"
    inline
/>
```

### Custom Color Themes

The DatePicker supports the enhanced color system:

```php
<!-- Predefined variant -->
<x-artisanpack-datepicker
    label="Event Date"
    wire:model="eventDate"
    color="primary"
/>

<!-- Tailwind color -->
<x-artisanpack-datepicker
    label="Deadline"
    wire:model="deadline"
    color="red-500"
/>

<!-- Custom hex color -->
<x-artisanpack-datepicker
    label="Special Date"
    wire:model="specialDate"
    color="#4ecdc4"
/>

<!-- With color adjustment -->
<x-artisanpack-datepicker
    label="Launch Date"
    wire:model="launchDate"
    color="blue-600"
    color-adjustment="lighter"
/>
```

### With Custom Configuration

```php
<x-artisanpack-datepicker
    label="Meeting Date"
    wire:model="meetingDate"
    :config="[
        'dateFormat' => 'Y-m-d',
        'minDate' => 'today',
        'maxDate' => date('Y-m-d', strtotime('+30 days')),
        'disable' => [
            function($date) {
                // Disable weekends
                return ($date->format('N') >= 6);
            }
        ]
    ]"
/>
```

### With Hints

```php
<x-artisanpack-datepicker
    label="Subscription Start"
    hint="Select your preferred start date"
    wire:model="subscriptionStart"
/>
```

### Dark Mode Support

```php
<x-artisanpack-datepicker
    label="Date"
    wire:model="date"
    dark-mode-support
/>
```

### Readonly DatePicker

```php
<x-artisanpack-datepicker
    label="Created At"
    wire:model="createdAt"
    readonly
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | Label text for the date picker |
| `icon` | string | `null` | Icon to display before the input |
| `iconRight` | string | `null` | Icon to display after the input |
| `hint` | string | `null` | Hint text displayed below the input |
| `hintClass` | string | `'fieldset-label'` | CSS classes for the hint text |
| `inline` | boolean | `false` | Display calendar inline instead of popup |
| `config` | array | `[]` | Flatpickr configuration options |
| `color` | string | `null` | Color theme - accepts variants, Tailwind colors, or hex codes |
| `colorAdjustment` | string | `null` | Background adjustment (`lighter`, `darker`, `subtle`, `transparent`) |
| `theme` | string | `'artisanpack'` | Calendar theme name |
| `fontConfig` | array | `[]` | Custom font configuration |
| `darkModeSupport` | boolean | `true` | Enable dark mode support |
| `prepend` | mixed | `null` | Content to prepend to the input |
| `append` | mixed | `null` | Content to append to the input |
| `errorField` | string | `null` | Field name for validation errors |
| `errorClass` | string | `'text-error'` | CSS classes for error messages |
| `omitError` | boolean | `false` | Hide error messages |
| `firstErrorOnly` | boolean | `false` | Show only the first error |

## Configuration Options

The DatePicker uses Flatpickr under the hood. You can pass any Flatpickr configuration options via the `config` prop:

### Common Configurations

```php
<!-- Date format -->
<x-artisanpack-datepicker
    :config="['dateFormat' => 'd/m/Y']"
    wire:model="date"
/>

<!-- Time picker -->
<x-artisanpack-datepicker
    :config="['enableTime' => true, 'time_24hr' => true]"
    wire:model="datetime"
/>

<!-- Date range -->
<x-artisanpack-datepicker
    :config="['mode' => 'range']"
    wire:model="dateRange"
/>

<!-- Multiple dates -->
<x-artisanpack-datepicker
    :config="['mode' => 'multiple']"
    wire:model="multipleDates"
/>

<!-- Week numbers -->
<x-artisanpack-datepicker
    :config="['weekNumbers' => true]"
    wire:model="date"
/>
```

## Validation

```php
// In your Livewire component
public $appointmentDate;

protected $rules = [
    'appointmentDate' => 'required|date|after:today',
];
```

```php
<!-- In your view -->
<x-artisanpack-datepicker
    label="Appointment Date"
    wire:model="appointmentDate"
    error-field="appointmentDate"
/>
```

## Color System

The DatePicker component fully supports the enhanced color system:

- **Predefined Variants**: `primary`, `secondary`, `accent`, `success`, `warning`, `error`, `info`
- **Tailwind Colors**: Any Tailwind color with intensity (e.g., `blue-500`, `red-600`)
- **Custom Hex Colors**: Any valid hex color code (e.g., `#ff6b6b`)
- **Color Adjustments**: `lighter`, `darker`, `subtle`, `transparent`

The color system applies to:
- Selected dates
- Today's date highlight
- Hover states
- Active month/year selector

## Slots

| Slot | Description |
|------|-------------|
| `prepend` | Content to display before the input field |
| `append` | Content to display after the input field |

### Slot Example

```php
<x-artisanpack-datepicker
    label="Event Date"
    wire:model="eventDate"
>
    <x-slot:prepend>
        <span class="text-gray-500">📅</span>
    </x-slot:prepend>
</x-artisanpack-datepicker>
```

## Accessibility

The DatePicker component follows accessibility best practices:

- Keyboard navigation support (arrows, Enter, Escape)
- ARIA labels and descriptions
- Screen reader announcements for date selection
- Proper focus management
- High contrast mode support

## Browser Support

The DatePicker component is compatible with all modern browsers that support Flatpickr.

## Related Components

- [DateTime](date-time) - Date and time picker combined
- [Input](input) - Basic text input
- [Calendar](calendar) - Full calendar view
