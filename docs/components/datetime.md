---
title: DateTime Component
---

# DateTime Component

The DateTime component is a form element that provides an interactive interface for selecting both date and time. It extends the functionality of the DatePicker component by adding time selection capabilities, offering a comprehensive solution for datetime inputs.

## Basic Usage

```php
<x-artisanpack-datetime label="Select Date and Time" />
```

## Examples

### Simple DateTime Picker

```php
<x-artisanpack-datetime 
    label="Appointment Date and Time" 
    name="appointment_datetime"
/>
```

### DateTime with Default Value

```php
<x-artisanpack-datetime 
    label="Start Date and Time" 
    value="2025-07-16 14:30:00"
/>
```

### DateTime with Placeholder

```php
<x-artisanpack-datetime 
    label="Meeting Time" 
    placeholder="Select meeting date and time"
/>
```

### Required DateTime

```php
<x-artisanpack-datetime 
    label="Event Date and Time" 
    required
/>
```

### Disabled DateTime

```php
<x-artisanpack-datetime 
    label="Scheduled Time" 
    value="2025-12-25 09:00:00" 
    disabled
/>
```

### DateTime with Min and Max Values

```php
<x-artisanpack-datetime 
    label="Booking Time" 
    min="2025-07-16 08:00:00" 
    max="2025-12-31 18:00:00"
/>
```

### DateTime with Helper Text

```php
<x-artisanpack-datetime 
    label="Delivery Time" 
    helper="Please select a date and time during business hours (9 AM - 5 PM)"
/>
```

### DateTime with Error

```php
<x-artisanpack-datetime 
    label="Event Time" 
    error="Please select a valid date and time"
/>
```

### DateTime with Livewire Binding

```php
<x-artisanpack-datetime 
    label="Event Time" 
    wire:model="eventDateTime"
/>

<!-- Lazy DateTime (updates on blur) -->
<x-artisanpack-datetime 
    label="Event Time" 
    wire:model.lazy="eventDateTime"
/>
```

### DateTime with Custom Format

```php
<x-artisanpack-datetime 
    label="Event Time" 
    format="MM/DD/YYYY hh:mm A"
/>
```

### DateTime with 24-Hour Format

```php
<x-artisanpack-datetime 
    label="Event Time" 
    format="YYYY-MM-DD HH:mm" 
    :hour-format="24"
/>
```

### DateTime with Time Interval

```php
<x-artisanpack-datetime 
    label="Appointment Time" 
    :time-interval="30"
/>
```

### DateTime with First Day of Week

```php
<x-artisanpack-datetime 
    label="Event Time" 
    :first-day-of-week="1" 
/>
```

### DateTime with Disabled Dates

```php
<x-artisanpack-datetime 
    label="Appointment Time" 
    :disabled-dates="['2025-07-20', '2025-07-21', '2025-07-22']"
/>
```

### DateTime with Custom Position

```php
<x-artisanpack-datetime 
    label="Event Time" 
    position="bottom-right"
/>
```

### DateTime with Autofocus

```php
<x-artisanpack-datetime 
    label="Event Time" 
    autofocus
/>
```

### DateTime Sizes

```php
<x-artisanpack-datetime 
    label="Small datetime" 
    size="sm"
/>

<x-artisanpack-datetime 
    label="Default datetime"
/>

<x-artisanpack-datetime 
    label="Large datetime" 
    size="lg"
/>
```

### DateTime with Prefix and Suffix

```php
<x-artisanpack-datetime 
    label="Event Time"
>
    <x-slot:prefix>
        <x-artisanpack-icon name="heroicon-o-clock" class="w-5 h-5" />
    </x-slot:prefix>
    
    <x-slot:suffix>
        <span class="text-gray-500">Date & Time</span>
    </x-slot:suffix>
</x-artisanpack-datetime>
```

### DateTime with Separate Date and Time Inputs

```php
<x-artisanpack-datetime 
    label="Event Time" 
    separate-inputs
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the datetime picker |
| `name` | string | `null` | The name attribute for the datetime picker |
| `id` | string | `null` | The ID attribute for the datetime picker (auto-generated if not provided) |
| `value` | string | `null` | The default value for the datetime picker (YYYY-MM-DD HH:MM:SS format) |
| `placeholder` | string | `null` | Placeholder text |
| `format` | string | `'YYYY-MM-DD HH:mm'` | The display format for the date and time |
| `min` | string | `null` | The minimum selectable date and time |
| `max` | string | `null` | The maximum selectable date and time |
| `disabled-dates` | array | `[]` | Array of dates that cannot be selected |
| `hour-format` | integer | `12` | Hour format (12 or 24) |
| `time-interval` | integer | `15` | Interval in minutes between time options |
| `first-day-of-week` | integer | `0` | First day of the week (0 = Sunday, 1 = Monday, etc.) |
| `position` | string | `'bottom-left'` | Position of the dropdown (`top-left`, `top-right`, `bottom-left`, `bottom-right`) |
| `separate-inputs` | boolean | `false` | Whether to show separate inputs for date and time |
| `required` | boolean | `false` | Whether the datetime picker is required |
| `disabled` | boolean | `false` | Whether the datetime picker is disabled |
| `readonly` | boolean | `false` | Whether the datetime picker is readonly |
| `autofocus` | boolean | `false` | Whether the datetime picker should be automatically focused |
| `size` | string | `'md'` | The size of the datetime picker (`sm`, `md`, `lg`) |
| `helper` | string | `null` | Helper text displayed below the datetime picker |
| `error` | string | `null` | Error message to display |

## Slots

| Slot | Description |
|------|-------------|
| `prefix` | Content to display before the datetime picker |
| `suffix` | Content to display after the datetime picker |

## Events

The DateTime component supports all standard HTML input events:

- `change`
- `focus`
- `blur`

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`

## Styling

The DateTime component uses DaisyUI's input component for the input field and a custom dropdown for date and time selection. You can customize the appearance by:

1. Using the provided props (`size`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-datetime 
    label="Custom styled datetime" 
    class="border-2 border-purple-500 focus:border-purple-700"
/>
```

## Accessibility

The DateTime component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Supports keyboard navigation for date and time selection
- Includes appropriate ARIA attributes
- Maintains focus management between input and dropdown
- Ensures adequate color contrast
- Provides clear date and time format information

## Related Components

- [Form](form) - Container for form elements
- [DatePicker](datepicker) - Date picker component
- [Input](input) - Standard text input
