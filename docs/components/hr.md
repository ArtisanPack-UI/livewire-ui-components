# Hr Component

The Hr component provides a horizontal rule (divider) with an optional loading indicator. It's useful for separating content sections and can also serve as a visual indicator for loading states.

## Basic Usage

```php
<x-artisanpack-hr />
```

## Examples

### Basic Horizontal Rule

```php
<x-artisanpack-hr />
```

### Horizontal Rule with Loading Indicator

```php
<x-artisanpack-hr target="loadData" />

<!-- Button that triggers the loading state -->
<x-artisanpack-button wire:click="loadData">Load Data</x-artisanpack-button>
```

### Horizontal Rule with Custom ID

```php
<x-artisanpack-hr id="section-divider" />
```

### Horizontal Rule with Custom Styling

```php
<x-artisanpack-hr class="my-8" />
```

### Horizontal Rule in a Card

```php
<x-artisanpack-card title="User Profile">
    <div>
        <h3>Personal Information</h3>
        <p>Name: John Doe</p>
        <p>Email: john@example.com</p>
    </div>
    
    <x-artisanpack-hr />
    
    <div>
        <h3>Account Settings</h3>
        <p>Last login: Yesterday</p>
        <p>Two-factor authentication: Enabled</p>
    </div>
</x-artisanpack-card>
```

### Horizontal Rule with Livewire Target

```php
<div>
    <h2>User List</h2>
    <div class="mb-4">
        <x-artisanpack-input wire:model.live="search" placeholder="Search users..." />
    </div>
    
    <x-artisanpack-hr target="search" />
    
    <div>
        <!-- User list content -->
    </div>
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the hr element |
| `target` | string\|null | `null` | Optional target for the loading indicator |

## Styling

The Hr component uses a combination of border and progress elements for styling. The horizontal rule is created using a border-top, and the loading indicator is implemented with a progress element.

### Default Classes

- `h-[2px]` - Height of the container
- `border-t-[length:var(--border)]` - Border thickness using the theme's border variable
- `border-t-base-content/10` - Border color with reduced opacity
- `my-5` - Vertical margin
- `progress progress-primary` - Progress bar styling
- `h-[1px]` - Height of the hidden progress bar
- `!h-[length:var(--border)] !block` - Height and display of the visible progress bar

## Loading Indicator

The Hr component includes a built-in loading indicator that appears when a Livewire action is in progress. To use this feature:

1. Set the `target` prop to the name of the Livewire method that triggers the loading state
2. The horizontal rule will automatically show a progress indicator when the specified method is running

This is particularly useful for indicating background operations like data loading or form submissions.

## Accessibility

The Hr component follows accessibility best practices:

- Uses appropriate semantic HTML
- Provides sufficient color contrast for visibility
- Includes visual feedback for loading states
- Maintains proper spacing for content separation

## Related Components

- [Separator](separator.md) - Alternative divider component
- [Card](card.md) - Content container that often uses dividers
- [Loading](loading.md) - Dedicated loading indicator component