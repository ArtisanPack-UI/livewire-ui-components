---
title: Step Component
---

# Step Component

The Step component represents an individual step within a multi-step process or workflow. It's designed to be used within the Steps component to create a complete step indicator.

## Basic Usage

```php
<x-artisanpack-steps>
    <x-artisanpack-step>Step 1</x-artisanpack-step>
    <x-artisanpack-step active>Step 2</x-artisanpack-step>
    <x-artisanpack-step>Step 3</x-artisanpack-step>
</x-artisanpack-steps>
```

## Examples

### Basic Step

```php
<x-artisanpack-steps>
    <x-artisanpack-step>Register</x-artisanpack-step>
    <x-artisanpack-step active>Profile</x-artisanpack-step>
    <x-artisanpack-step>Complete</x-artisanpack-step>
</x-artisanpack-steps>
```

### Step with Icon

```php
<x-artisanpack-steps>
    <x-artisanpack-step>
        <x-artisanpack-icon name="heroicon-o-user-plus" class="w-5 h-5 mr-2" />
        Register
    </x-artisanpack-step>
    <x-artisanpack-step active>
        <x-artisanpack-icon name="heroicon-o-user" class="w-5 h-5 mr-2" />
        Profile
    </x-artisanpack-step>
    <x-artisanpack-step>
        <x-artisanpack-icon name="heroicon-o-check-circle" class="w-5 h-5 mr-2" />
        Complete
    </x-artisanpack-step>
</x-artisanpack-steps>
```

### Step States

```php
<x-artisanpack-steps>
    <!-- Completed Step -->
    <x-artisanpack-step completed>
        <x-artisanpack-icon name="heroicon-o-check" class="w-5 h-5 mr-2" />
        Account
    </x-artisanpack-step>
    
    <!-- Active Step -->
    <x-artisanpack-step active>
        <x-artisanpack-icon name="heroicon-o-credit-card" class="w-5 h-5 mr-2" />
        Payment
    </x-artisanpack-step>
    
    <!-- Disabled Step -->
    <x-artisanpack-step disabled>
        <x-artisanpack-icon name="heroicon-o-truck" class="w-5 h-5 mr-2" />
        Shipping
    </x-artisanpack-step>
    
    <!-- Regular Step (not yet reached) -->
    <x-artisanpack-step>
        <x-artisanpack-icon name="heroicon-o-check-circle" class="w-5 h-5 mr-2" />
        Confirm
    </x-artisanpack-step>
</x-artisanpack-steps>
```

### Step with Link

```php
<x-artisanpack-steps>
    <x-artisanpack-step href="/register" completed>Register</x-artisanpack-step>
    <x-artisanpack-step href="/profile" active>Profile</x-artisanpack-step>
    <x-artisanpack-step href="/complete" disabled>Complete</x-artisanpack-step>
</x-artisanpack-steps>
```

### Step with Custom Content

```php
<x-artisanpack-steps>
    <x-artisanpack-step completed>
        <div class="flex flex-col items-center">
            <x-artisanpack-icon name="heroicon-o-check-circle" class="w-6 h-6 mb-1 text-success" />
            <span>Account</span>
            <span class="text-xs opacity-70">Completed</span>
        </div>
    </x-artisanpack-step>
    
    <x-artisanpack-step active>
        <div class="flex flex-col items-center">
            <x-artisanpack-icon name="heroicon-o-credit-card" class="w-6 h-6 mb-1" />
            <span>Payment</span>
            <span class="text-xs opacity-70">In progress</span>
        </div>
    </x-artisanpack-step>
    
    <x-artisanpack-step>
        <div class="flex flex-col items-center">
            <x-artisanpack-icon name="heroicon-o-truck" class="w-6 h-6 mb-1" />
            <span>Shipping</span>
            <span class="text-xs opacity-70">Not started</span>
        </div>
    </x-artisanpack-step>
</x-artisanpack-steps>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `active` | boolean | `false` | Whether this step is the current active step |
| `completed` | boolean | `false` | Whether this step has been completed |
| `disabled` | boolean | `false` | Whether this step is disabled or not yet available |
| `href` | string | `null` | Optional URL to make the step clickable |
| `id` | string | `null` | Optional ID for the step element |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The content of the step, typically text or icons |

## Behavior

The Step component:

1. Represents a single step within a multi-step process
2. Can display different visual states (active, completed, disabled)
3. Can be made clickable by providing an `href` prop
4. Is designed to be used within the Steps component
5. Automatically connects to adjacent steps with a line or connector

## Styling

The Step component uses DaisyUI's step component under the hood, which provides a clean and consistent appearance. You can customize the appearance by:

1. Using the provided props (`active`, `completed`, `disabled`)
2. Adding custom classes via the `class` attribute
3. Customizing the content with icons, text, or other elements

### Default Classes

- Active steps are highlighted with the primary color
- Completed steps typically show a check mark or completion indicator
- Disabled steps have reduced opacity
- Steps are connected with lines to show progression

## Accessibility

The Step component follows accessibility best practices:

- Uses appropriate ARIA attributes based on the step's state
- Maintains proper color contrast for all states
- Supports keyboard navigation when steps are clickable

For better accessibility:

1. Include clear, descriptive text for each step
2. Use icons as supplements to text, not replacements
3. Ensure that the current step is clearly indicated both visually and programmatically

## Related Components

- [Steps](steps) - Container component for multiple steps
- [Progress](progress) - Alternative way to show completion status
- [Breadcrumbs](breadcrumbs) - Navigation component showing the user's location in a hierarchy