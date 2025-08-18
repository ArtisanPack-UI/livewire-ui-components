---
title: DatePicker Component
---

# DatePicker Component

The DatePicker component is a form element that provides an interactive calendar interface for selecting dates. It offers a user-friendly alternative to the standard date input with enhanced functionality and styling.

## Installation & Setup

The DatePicker component requires additional setup to function properly in your application.

### 1. Install FlatPickr Dependency

The DatePicker component uses FlatPickr under the hood. You need to install it as a dependency:

```bash
npm install flatpickr
```

### 2. Configure Assets

Add FlatPickr and its plugins to your JavaScript build process. In your `resources/js/app.js` file:

```javascript
import './bootstrap';

// Import flatpickr and make it globally available
import flatpickr from 'flatpickr';
import weekSelect from 'flatpickr/dist/plugins/weekSelect/weekSelect.js';

// Import flatpickr CSS
import 'flatpickr/dist/flatpickr.min.css';

// Make flatpickr and plugins globally available
window.flatpickr = flatpickr;
window.weekSelect = weekSelect;
```

### 3. Publish and Import Theme Assets

The DatePicker includes custom theming. Publish the theme assets:

```bash
php artisan vendor:publish --tag=artisanpack-assets
```

Then import the DatePicker theme CSS in your `resources/css/app.css`:

```css
@import url('../../public/vendor/artisanpack-ui/css/datepicker-theme.css');
```

### 4. Build Assets

After making these changes, rebuild your assets:

```bash
npm run build
# or for development
npm run dev
```

### 5. Theming Configuration

The DatePicker supports extensive theming through the following properties:

- `color` - Set custom colors (e.g., "primary", "secondary", or hex values)
- `color-adjustment` - Modify color intensity ("lighter", "darker", "subtle", "transparent")
- `theme` - Theme variant (default: "artisanpack")
- `dark-mode-support` - Enable dark mode theming (default: true)
- `font-config` - Custom font configuration array

Example with theming:

```php
<x-artisanpack-datepicker 
    label="Themed DatePicker" 
    color="primary"
    color-adjustment="subtle"
    :font-config="[
        'font-family' => 'Inter, sans-serif',
        'font-size' => '0.875rem',
        'font-weight' => '400'
    ]"
/>
```

## Troubleshooting

### Common Issues

**Error: "Can't find variable: flatpickr"**
- Ensure you've installed the flatpickr dependency: `npm install flatpickr`
- Verify flatpickr is imported and made globally available in your `app.js`
- Rebuild your assets: `npm run build`

**Error: "Can't find variable: weekSelect"**
- Ensure the weekSelect plugin is imported in your `app.js`
- Make sure it's made globally available: `window.weekSelect = weekSelect`

**DatePicker appears unstyled or with incorrect theming**
- Publish the theme assets: `php artisan vendor:publish --tag=artisanpack-assets`
- Import the theme CSS in your `app.css`
- Rebuild your assets: `npm run build`

**Livewire errors about component not found**
- Ensure you have wire:model attribute if using Livewire binding
- Check that the Livewire component context is available

## Basic Usage

```php
<x-artisanpack-datepicker label="Select Date" />
```

## Examples

### Simple DatePicker

```php
<x-artisanpack-datepicker 
    label="Appointment Date" 
    name="appointment_date"
/>
```

### DatePicker with Default Value

```php
<x-artisanpack-datepicker 
    label="Start Date" 
    value="2025-07-16"
/>
```

### DatePicker with Placeholder

```php
<x-artisanpack-datepicker 
    label="Birth Date" 
    placeholder="Select your birth date"
/>
```

### Required DatePicker

```php
<x-artisanpack-datepicker 
    label="Event Date" 
    required
/>
```

### Disabled DatePicker

```php
<x-artisanpack-datepicker 
    label="Holiday Date" 
    value="2025-12-25" 
    disabled
/>
```

### DatePicker with Min and Max Dates

```php
<x-artisanpack-datepicker 
    label="Booking Date" 
    min="2025-07-16" 
    max="2025-12-31"
/>
```

### DatePicker with Helper Text

```php
<x-artisanpack-datepicker 
    label="Delivery Date" 
    helper="Please select a date at least 3 days from today"
/>
```

### DatePicker with Error

```php
<x-artisanpack-datepicker 
    label="Event Date" 
    error="Please select a valid date"
/>
```

### DatePicker with Livewire Binding

```php
<x-artisanpack-datepicker 
    label="Event Date" 
    wire:model="eventDate"
/>

<!-- Lazy DatePicker (updates on blur) -->
<x-artisanpack-datepicker 
    label="Event Date" 
    wire:model.lazy="eventDate"
/>
```

### DatePicker with Custom Format

```php
<x-artisanpack-datepicker 
    label="Event Date" 
    format="MM/DD/YYYY"
/>
```

### DatePicker with First Day of Week

```php
<x-artisanpack-datepicker 
    label="Event Date" 
    first-day-of-week="1" 
/>
```

### DatePicker with Disabled Dates

```php
<x-artisanpack-datepicker 
    label="Appointment Date" 
    :disabled-dates="['2025-07-20', '2025-07-21', '2025-07-22']"
/>
```

### DatePicker with Highlighted Dates

```php
<x-artisanpack-datepicker 
    label="Event Date" 
    :highlighted-dates="['2025-07-25', '2025-07-26', '2025-07-27']"
/>
```

### DatePicker with Custom Position

```php
<x-artisanpack-datepicker 
    label="Event Date" 
    position="bottom-right"
/>
```

### DatePicker with Autofocus

```php
<x-artisanpack-datepicker 
    label="Event Date" 
    autofocus
/>
```

### DatePicker Sizes

```php
<x-artisanpack-datepicker 
    label="Small datepicker" 
    size="sm"
/>

<x-artisanpack-datepicker 
    label="Default datepicker"
/>

<x-artisanpack-datepicker 
    label="Large datepicker" 
    size="lg"
/>
```

### DatePicker with Prefix and Suffix

```php
<x-artisanpack-datepicker 
    label="Event Date"
>
    <x-slot:prefix>
        <x-artisanpack-icon name="heroicon-o-calendar" class="w-5 h-5" />
    </x-slot:prefix>
    
    <x-slot:suffix>
        <span class="text-gray-500">Date</span>
    </x-slot:suffix>
</x-artisanpack-datepicker>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the datepicker |
| `name` | string | `null` | The name attribute for the datepicker |
| `id` | string | `null` | The ID attribute for the datepicker (auto-generated if not provided) |
| `value` | string | `null` | The default value for the datepicker (YYYY-MM-DD format) |
| `placeholder` | string | `null` | Placeholder text |
| `format` | string | `'YYYY-MM-DD'` | The display format for the date |
| `min` | string | `null` | The minimum selectable date (YYYY-MM-DD format) |
| `max` | string | `null` | The maximum selectable date (YYYY-MM-DD format) |
| `disabled-dates` | array | `[]` | Array of dates that cannot be selected (YYYY-MM-DD format) |
| `highlighted-dates` | array | `[]` | Array of dates to highlight (YYYY-MM-DD format) |
| `first-day-of-week` | integer | `0` | First day of the week (0 = Sunday, 1 = Monday, etc.) |
| `position` | string | `'bottom-left'` | Position of the calendar dropdown (`top-left`, `top-right`, `bottom-left`, `bottom-right`) |
| `required` | boolean | `false` | Whether the datepicker is required |
| `disabled` | boolean | `false` | Whether the datepicker is disabled |
| `readonly` | boolean | `false` | Whether the datepicker is readonly |
| `autofocus` | boolean | `false` | Whether the datepicker should be automatically focused |
| `size` | string | `'md'` | The size of the datepicker (`sm`, `md`, `lg`) |
| `helper` | string | `null` | Helper text displayed below the datepicker |
| `error` | string | `null` | Error message to display |

## Slots

| Slot | Description |
|------|-------------|
| `prefix` | Content to display before the datepicker |
| `suffix` | Content to display after the datepicker |

## Events

The DatePicker component supports all standard HTML input events:

- `change`
- `focus`
- `blur`

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`

## Styling

The DatePicker component uses DaisyUI's input component for the input field and a custom calendar dropdown. You can customize the appearance by:

1. Using the provided props (`size`, etc.)
2. Adding custom classes via the `class` attribute
3. Modifying the DaisyUI variables in your theme file

### Custom Styling Example

```php
<x-artisanpack-datepicker 
    label="Custom styled datepicker" 
    class="border-2 border-purple-500 focus:border-purple-700"
/>
```

## Accessibility

The DatePicker component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Supports keyboard navigation for date selection
- Includes appropriate ARIA attributes
- Maintains focus management between input and calendar
- Ensures adequate color contrast
- Provides clear date format information

## Related Components

- [Form](form.md) - Container for form elements
- [Input](input.md) - Standard text input
- [DateTime](datetime.md) - Date and time picker component
