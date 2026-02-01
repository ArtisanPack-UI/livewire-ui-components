---
title: Radio Group Component
---

# Radio Group Component

The Radio Group component displays a group of radio buttons, allowing users to select a single option from a set of choices.

## Basic Usage

```php
<x-artisanpack-radio-group
    label="Select your preference"
    :options="$preferences"
    wire:model="selectedPreference"
/>
```

## Examples

### Simple Radio Group

```php
<x-artisanpack-radio-group
    label="Size"
    :options="[
        ['id' => 'small', 'name' => 'Small'],
        ['id' => 'medium', 'name' => 'Medium'],
        ['id' => 'large', 'name' => 'Large'],
        ['id' => 'xlarge', 'name' => 'Extra Large'],
    ]"
    wire:model="size"
/>
```

### Horizontal Layout

```php
<x-artisanpack-radio-group
    label="Gender"
    :options="$genderOptions"
    wire:model="gender"
    horizontal
/>
```

### Card Style Radio Buttons

```php
<x-artisanpack-radio-group
    label="Choose Plan"
    :options="$plans"
    wire:model="selectedPlan"
    card
/>
```

### With Hints

```php
<x-artisanpack-radio-group
    label="Subscription Tier"
    hint="Choose the plan that fits your needs"
    :options="[
        ['id' => 'basic', 'name' => 'Basic', 'hint' => '$9/month - Essential features'],
        ['id' => 'pro', 'name' => 'Pro', 'hint' => '$29/month - Advanced features'],
        ['id' => 'enterprise', 'name' => 'Enterprise', 'hint' => '$99/month - Full access'],
    ]"
    wire:model="subscriptionTier"
    option-hint="hint"
/>
```

### Custom Option Keys

```php
<x-artisanpack-radio-group
    label="Country"
    :options="$countries"
    wire:model="country"
    option-value="code"
    option-label="country_name"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | Label text for the radio group |
| `hint` | string | `null` | Hint text displayed below the label |
| `hintClass` | string | `'fieldset-label'` | CSS classes for the hint text |
| `options` | Collection\|array | `[]` | Array or Collection of options |
| `optionValue` | string | `'id'` | Key name for the option value |
| `optionLabel` | string | `'name'` | Key name for the option label |
| `optionHint` | string | `'hint'` | Key name for the option hint |
| `horizontal` | boolean | `false` | Display radio buttons horizontally |
| `card` | boolean | `false` | Display radio buttons as cards |
| `cardClass` | string | `'card card-compact border-2 border-base-300 hover:border-primary cursor-pointer transition-colors'` | CSS classes for card style |
| `errorField` | string | `null` | Field name for validation errors |
| `errorClass` | string | `'text-error'` | CSS classes for error messages |
| `omitError` | boolean | `false` | Hide error messages |
| `firstErrorOnly` | boolean | `false` | Show only the first error |

## Validation

The component integrates with Laravel's validation system:

```php
// In your Livewire component
public string $selectedOption = '';

protected $rules = [
    'selectedOption' => 'required|in:option1,option2,option3',
];

public function save()
{
    $this->validate();
    // Process selected option
}
```

```php
<!-- In your view -->
<x-artisanpack-radio-group
    label="Required Selection"
    :options="$options"
    wire:model="selectedOption"
    error-field="selectedOption"
/>
```

## Styling

### Custom Card Styling

```php
<x-artisanpack-radio-group
    label="Shipping Method"
    :options="$shippingMethods"
    wire:model="selectedShipping"
    card
    card-class="card border-2 border-primary hover:border-primary-focus bg-base-100 p-4"
/>
```

### Custom Hint Styling

```php
<x-artisanpack-radio-group
    label="Notification Preference"
    hint="Choose how you'd like to receive notifications"
    hint-class="text-sm text-gray-500 font-medium"
    :options="$notificationOptions"
    wire:model="notificationPreference"
/>
```

## Accessibility

The Radio Group component follows accessibility best practices:

- Uses semantic fieldset and legend elements
- Each radio button has a properly associated label
- Keyboard navigation support (Tab, Arrow keys, Space)
- ARIA attributes for screen readers
- Proper focus management
- Only one radio button can be selected at a time per group

## Related Components

- [Radio](Radio) - Single radio button input
- [Checkbox Group](Checkbox-Group) - Group of checkboxes (multiple selection)
- [Select](Select) - Dropdown selection
