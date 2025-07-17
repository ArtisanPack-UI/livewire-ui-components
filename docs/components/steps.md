# Steps Component

The Steps component provides a visual representation of progress through a sequence of steps or stages. It's ideal for multi-step forms, wizards, onboarding flows, and any process that involves a series of actions.

## Basic Usage

```php
<x-artisanpack-steps>
    <x-artisanpack-step>Step 1</x-artisanpack-step>
    <x-artisanpack-step active>Step 2</x-artisanpack-step>
    <x-artisanpack-step>Step 3</x-artisanpack-step>
</x-artisanpack-steps>
```

## Examples

### Basic Steps

```php
<x-artisanpack-steps>
    <x-artisanpack-step>Register</x-artisanpack-step>
    <x-artisanpack-step active>Profile</x-artisanpack-step>
    <x-artisanpack-step>Complete</x-artisanpack-step>
</x-artisanpack-steps>
```

### Steps with Different States

```php
<x-artisanpack-steps>
    <x-artisanpack-step completed>Account</x-artisanpack-step>
    <x-artisanpack-step active>Payment</x-artisanpack-step>
    <x-artisanpack-step>Shipping</x-artisanpack-step>
    <x-artisanpack-step>Review</x-artisanpack-step>
</x-artisanpack-steps>
```

### Vertical Steps

```php
<x-artisanpack-steps vertical>
    <x-artisanpack-step completed>Account</x-artisanpack-step>
    <x-artisanpack-step active>Payment</x-artisanpack-step>
    <x-artisanpack-step>Shipping</x-artisanpack-step>
    <x-artisanpack-step>Review</x-artisanpack-step>
</x-artisanpack-steps>
```

### Steps with Icons

```php
<x-artisanpack-steps>
    <x-artisanpack-step completed>
        <x-artisanpack-icon name="heroicon-o-user" class="w-5 h-5 mr-2" />
        Account
    </x-artisanpack-step>
    
    <x-artisanpack-step active>
        <x-artisanpack-icon name="heroicon-o-credit-card" class="w-5 h-5 mr-2" />
        Payment
    </x-artisanpack-step>
    
    <x-artisanpack-step>
        <x-artisanpack-icon name="heroicon-o-truck" class="w-5 h-5 mr-2" />
        Shipping
    </x-artisanpack-step>
    
    <x-artisanpack-step>
        <x-artisanpack-icon name="heroicon-o-check-circle" class="w-5 h-5 mr-2" />
        Complete
    </x-artisanpack-step>
</x-artisanpack-steps>
```

### Steps with Custom Colors

```php
<x-artisanpack-steps class="steps-primary">
    <x-artisanpack-step completed>Account</x-artisanpack-step>
    <x-artisanpack-step active>Payment</x-artisanpack-step>
    <x-artisanpack-step>Shipping</x-artisanpack-step>
</x-artisanpack-steps>

<x-artisanpack-steps class="steps-secondary">
    <x-artisanpack-step completed>Account</x-artisanpack-step>
    <x-artisanpack-step active>Payment</x-artisanpack-step>
    <x-artisanpack-step>Shipping</x-artisanpack-step>
</x-artisanpack-steps>

<x-artisanpack-steps class="steps-accent">
    <x-artisanpack-step completed>Account</x-artisanpack-step>
    <x-artisanpack-step active>Payment</x-artisanpack-step>
    <x-artisanpack-step>Shipping</x-artisanpack-step>
</x-artisanpack-steps>
```

### Steps with Numbers

```php
<x-artisanpack-steps numbered>
    <x-artisanpack-step completed>Account</x-artisanpack-step>
    <x-artisanpack-step active>Payment</x-artisanpack-step>
    <x-artisanpack-step>Shipping</x-artisanpack-step>
    <x-artisanpack-step>Review</x-artisanpack-step>
</x-artisanpack-steps>
```

### Responsive Steps

```php
<x-artisanpack-steps class="lg:steps-horizontal steps-vertical">
    <x-artisanpack-step completed>Account</x-artisanpack-step>
    <x-artisanpack-step active>Payment</x-artisanpack-step>
    <x-artisanpack-step>Shipping</x-artisanpack-step>
    <x-artisanpack-step>Review</x-artisanpack-step>
</x-artisanpack-steps>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `vertical` | boolean | `false` | Whether to display steps vertically instead of horizontally |
| `numbered` | boolean | `false` | Whether to display numbers for each step |
| `id` | string | `null` | Optional ID for the steps container |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Contains the Step components that make up the steps sequence |

## Behavior

The Steps component:

1. Displays a sequence of steps in a horizontal or vertical layout
2. Automatically connects steps with lines or connectors
3. Supports different states for steps (active, completed, disabled)
4. Can display numbers for each step when the `numbered` prop is used
5. Adapts to the number of steps provided

## Styling

The Steps component uses DaisyUI's steps component under the hood, which provides a clean and consistent appearance. You can customize the appearance by:

1. Using the provided props (`vertical`, `numbered`)
2. Adding custom classes via the `class` attribute
3. Using DaisyUI's color classes (e.g., `steps-primary`, `steps-secondary`)

### Default Classes

- Horizontal steps are displayed in a row with connecting lines
- Vertical steps are displayed in a column with connecting lines
- Active steps are highlighted with the primary color
- Completed steps typically show a check mark or completion indicator
- Numbered steps display the step number instead of an icon

## Accessibility

The Steps component follows accessibility best practices:

- Uses appropriate ARIA attributes to indicate the current step
- Maintains proper color contrast for all states
- Supports keyboard navigation when steps are clickable

For better accessibility:

1. Include clear, descriptive text for each step
2. Use icons as supplements to text, not replacements
3. Ensure that the current step is clearly indicated both visually and programmatically
4. Consider adding additional context for screen readers about the total number of steps and current progress

## Related Components

- [Step](step.md) - Individual step component used within Steps
- [Progress](progress.md) - Alternative way to show completion status
- [Breadcrumbs](breadcrumbs.md) - Navigation component showing the user's location in a hierarchy