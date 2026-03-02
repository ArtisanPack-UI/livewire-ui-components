---
title: DateTime Component
---

# DateTime Component

The DateTime component provides a combined date and time picker, allowing users to select both date and time in a single input field.

## Basic Usage

```php
<x-artisanpack-datetime
    label="Appointment"
    wire:model="appointmentDateTime"
/>
```

## Examples

### Basic DateTime Picker

```php
<x-artisanpack-datetime
    label="Meeting Time"
    wire:model="meetingTime"
/>
```

### With Icons

```php
<x-artisanpack-datetime
    label="Event Start"
    icon="o-clock"
    wire:model="eventStart"
/>

<x-artisanpack-datetime
    label="Deadline"
    icon-right="o-calendar"
    wire:model="deadline"
/>
```

### Inline DateTime Picker

```php
<x-artisanpack-datetime
    label="Select DateTime"
    wire:model="selectedDateTime"
    inline
/>
```

### With Hints

```php
<x-artisanpack-datetime
    label="Scheduled Publication"
    hint="Select the date and time to publish this content"
    wire:model="publishAt"
/>
```

### Custom Time Format

The DateTime component uses Flatpickr's datetime features:

```php
<x-artisanpack-datetime
    label="Appointment"
    wire:model="appointment"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | Label text for the datetime picker |
| `icon` | string | `null` | Icon to display before the input |
| `iconRight` | string | `null` | Icon to display after the input |
| `hint` | string | `null` | Hint text displayed below the input |
| `hintClass` | string | `'fieldset-label'` | CSS classes for the hint text |
| `inline` | boolean | `false` | Display calendar inline instead of popup |
| `prepend` | mixed | `null` | Content to prepend to the input |
| `append` | mixed | `null` | Content to append to the input |
| `errorField` | string | `null` | Field name for validation errors |
| `errorClass` | string | `'text-error'` | CSS classes for error messages |
| `omitError` | boolean | `false` | Hide error messages |
| `firstErrorOnly` | boolean | `false` | Show only the first error |

## Validation

```php
// In your Livewire component
public $meetingDateTime;

protected $rules = [
    'meetingDateTime' => 'required|date|after:now',
];
```

```php
<!-- In your view -->
<x-artisanpack-datetime
    label="Meeting Time"
    wire:model="meetingDateTime"
    error-field="meetingDateTime"
/>
```

## Slots

| Slot | Description |
|------|-------------|
| `prepend` | Content to display before the input field |
| `append` | Content to display after the input field |

### Slot Example

```php
<x-artisanpack-datetime
    label="Event Time"
    wire:model="eventTime"
>
    <x-slot:prepend>
        <span class="text-gray-500">🕐</span>
    </x-slot:prepend>
</x-artisanpack-datetime>
```

## Accessibility

The DateTime component follows accessibility best practices:

- Keyboard navigation support (arrows, Enter, Escape, Tab)
- ARIA labels and descriptions
- Screen reader announcements for datetime selection
- Proper focus management
- High contrast mode support

## Related Components

- [DatePicker](date-picker) - Date picker only
- [Input](input) - Basic text input
- [Calendar](calendar) - Full calendar view
