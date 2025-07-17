# Errors Component

The Errors component provides a formatted way to display validation errors in your application. It automatically displays all validation errors or can be configured to show only specific ones.

## Basic Usage

```php
<x-artisanpack-errors />
```

## Examples

### Basic Errors Display

```php
<x-artisanpack-errors />
```

### Errors with Title and Description

```php
<x-artisanpack-errors 
    title="Form Validation Failed"
    description="Please fix the following errors:"
/>
```

### Errors with Custom Icon

```php
<x-artisanpack-errors 
    title="Validation Errors"
    icon="o-exclamation-triangle"
/>
```

### Errors with Custom Styling

```php
<x-artisanpack-errors 
    title="Validation Errors"
    class="bg-red-50 text-red-700 border border-red-200 p-4"
/>
```

### Displaying Only Specific Errors

```php
<x-artisanpack-errors 
    title="Email Validation Errors"
    :only="['email']"
/>
```

### Errors in a Form

```php
<form wire:submit.prevent="saveUser">
    <div class="space-y-4">
        <x-artisanpack-input label="Name" wire:model="name" />
        <x-artisanpack-input label="Email" wire:model="email" type="email" />
        <x-artisanpack-input label="Password" wire:model="password" type="password" />
        
        <x-artisanpack-errors 
            title="Please fix the following issues:"
            class="mt-4"
        />
        
        <div class="mt-6">
            <x-artisanpack-button type="submit">Save User</x-artisanpack-button>
        </div>
    </div>
</form>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the errors element |
| `title` | string\|null | `null` | Optional title for the errors section |
| `description` | string\|null | `null` | Optional description for the errors section |
| `icon` | string\|null | `'o-x-circle'` | Icon to display next to the title |
| `only` | array\|null | `[]` | Array of specific error keys to display (empty array means all errors are displayed) |

## Styling

The Errors component uses DaisyUI's alert component with error styling by default. You can customize the appearance by adding custom classes via the `class` attribute.

### Default Classes

- `alert` - Base alert class
- `alert-error` - Error styling
- `rounded rounded-sm` - Rounded corners

## Accessibility

The Errors component follows accessibility best practices:

- Uses semantic HTML with proper structure
- Displays errors in a list format for better screen reader support
- Includes visual indicators (icon and color) for error state
- Provides clear error messages with optional title and description

## Related Components

- [Alert](alert.md) - General purpose alert messages
- [Toast](toast.md) - Temporary notifications
- [Form](form.md) - Form container component