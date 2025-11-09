---
title: Checkbox Group Component
---

# Checkbox Group Component

The Checkbox Group component displays a group of related checkboxes, allowing users to select multiple options from a set of choices.

## Basic Usage

```php
<x-artisanpack-checkbox-group
    label="Select your interests"
    :options="$interests"
    wire:model="selectedInterests"
/>
```

## Examples

### Simple Checkbox Group

```php
<x-artisanpack-checkbox-group
    label="Technologies"
    :options="[
        ['id' => 1, 'name' => 'Laravel'],
        ['id' => 2, 'name' => 'Livewire'],
        ['id' => 3, 'name' => 'Alpine.js'],
        ['id' => 4, 'name' => 'Tailwind CSS'],
    ]"
    wire:model="technologies"
/>
```

### Horizontal Layout

```php
<x-artisanpack-checkbox-group
    label="Preferences"
    :options="$preferences"
    wire:model="userPreferences"
    horizontal
/>
```

### Card Style Checkboxes

```php
<x-artisanpack-checkbox-group
    label="Select Features"
    :options="$features"
    wire:model="selectedFeatures"
    card
/>
```

### With Hints

```php
<x-artisanpack-checkbox-group
    label="Services"
    hint="Select all that apply"
    :options="[
        ['id' => 1, 'name' => 'Email Support', 'hint' => '24/7 support'],
        ['id' => 2, 'name' => 'Phone Support', 'hint' => 'Business hours'],
        ['id' => 3, 'name' => 'Live Chat', 'hint' => 'Instant responses'],
    ]"
    wire:model="services"
    option-hint="hint"
/>
```

### Custom Option Keys

```php
<x-artisanpack-checkbox-group
    label="Countries"
    :options="$countries"
    wire:model="selectedCountries"
    option-value="code"
    option-label="country_name"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | Label text for the checkbox group |
| `hint` | string | `null` | Hint text displayed below the label |
| `hintClass` | string | `'fieldset-label'` | CSS classes for the hint text |
| `options` | Collection\|array | `[]` | Array or Collection of options |
| `optionValue` | string | `'id'` | Key name for the option value |
| `optionLabel` | string | `'name'` | Key name for the option label |
| `optionHint` | string | `'hint'` | Key name for the option hint |
| `horizontal` | boolean | `false` | Display checkboxes horizontally |
| `card` | boolean | `false` | Display checkboxes as cards |
| `cardClass` | string | `'card card-compact border-2 border-base-300 hover:border-primary cursor-pointer transition-colors'` | CSS classes for card style |
| `errorField` | string | `null` | Field name for validation errors |
| `errorClass` | string | `'text-error'` | CSS classes for error messages |
| `omitError` | boolean | `false` | Hide error messages |
| `firstErrorOnly` | boolean | `false` | Show only the first error |

## Validation

The component integrates with Laravel's validation system:

```php
// In your Livewire component
public array $selectedOptions = [];

protected $rules = [
    'selectedOptions' => 'required|array|min:1',
];

public function save()
{
    $this->validate();
    // Process selected options
}
```

```php
<!-- In your view -->
<x-artisanpack-checkbox-group
    label="Required Selection"
    :options="$options"
    wire:model="selectedOptions"
    error-field="selectedOptions"
/>
```

## Styling

### Custom Card Styling

```php
<x-artisanpack-checkbox-group
    label="Premium Options"
    :options="$premiumOptions"
    wire:model="selectedPremium"
    card
    card-class="card border-2 border-accent hover:border-accent-focus bg-base-100"
/>
```

### Custom Hint Styling

```php
<x-artisanpack-checkbox-group
    label="Configuration"
    hint="Choose your setup options"
    hint-class="text-sm text-gray-500 italic"
    :options="$configOptions"
    wire:model="config"
/>
```

## Accessibility

The Checkbox Group component follows accessibility best practices:

- Uses semantic fieldset and legend elements
- Each checkbox has a properly associated label
- Keyboard navigation support
- ARIA attributes for screen readers
- Proper focus management

## Related Components

- [Checkbox](checkbox) - Single checkbox input
- [Radio Group](radio-group) - Group of radio buttons (single selection)
- [Select](select) - Dropdown selection
