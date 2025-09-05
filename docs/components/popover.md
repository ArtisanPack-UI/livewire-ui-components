---
title: Popover Component
---

# Popover Component

The Popover component displays additional content when hovering over a trigger element. It's useful for providing contextual information, tooltips, or additional options without requiring a click or taking up permanent space in the UI.

## Basic Usage

```php
<x-artisanpack-popover>
    <x-slot:trigger>
        <x-artisanpack-button>Hover Me</x-artisanpack-button>
    </x-slot:trigger>
    
    <x-slot:content>
        <div class="p-2">
            <h3 class="font-bold">Popover Title</h3>
            <p>This is the popover content.</p>
        </div>
    </x-slot:content>
</x-artisanpack-popover>
```

## Examples

### Basic Popover

```php
<x-artisanpack-popover>
    <x-slot:trigger>
        <x-artisanpack-button>Hover Me</x-artisanpack-button>
    </x-slot:trigger>
    
    <x-slot:content>
        <div class="p-2">
            <h3 class="font-bold">Popover Title</h3>
            <p>This is the popover content.</p>
        </div>
    </x-slot:content>
</x-artisanpack-popover>
```

### Different Positions

```php
<!-- Top Position -->
<x-artisanpack-popover position="top">
    <x-slot:trigger>
        <x-artisanpack-button>Top Popover</x-artisanpack-button>
    </x-slot:trigger>
    
    <x-slot:content>
        <p>This popover appears above the trigger.</p>
    </x-slot:content>
</x-artisanpack-popover>

<!-- Right Position -->
<x-artisanpack-popover position="right">
    <x-slot:trigger>
        <x-artisanpack-button>Right Popover</x-artisanpack-button>
    </x-slot:trigger>
    
    <x-slot:content>
        <p>This popover appears to the right of the trigger.</p>
    </x-slot:content>
</x-artisanpack-popover>

<!-- Bottom Position (Default) -->
<x-artisanpack-popover position="bottom">
    <x-slot:trigger>
        <x-artisanpack-button>Bottom Popover</x-artisanpack-button>
    </x-slot:trigger>
    
    <x-slot:content>
        <p>This popover appears below the trigger.</p>
    </x-slot:content>
</x-artisanpack-popover>

<!-- Left Position -->
<x-artisanpack-popover position="left">
    <x-slot:trigger>
        <x-artisanpack-button>Left Popover</x-artisanpack-button>
    </x-slot:trigger>
    
    <x-slot:content>
        <p>This popover appears to the left of the trigger.</p>
    </x-slot:content>
</x-artisanpack-popover>
```

### Custom Offset

```php
<x-artisanpack-popover offset="20">
    <x-slot:trigger>
        <x-artisanpack-button>Hover Me</x-artisanpack-button>
    </x-slot:trigger>
    
    <x-slot:content>
        <p>This popover has a larger offset from the trigger.</p>
    </x-slot:content>
</x-artisanpack-popover>
```

### Rich Content in Popover

```php
<x-artisanpack-popover>
    <x-slot:trigger>
        <x-artisanpack-button>User Profile</x-artisanpack-button>
    </x-slot:trigger>
    
    <x-slot:content>
        <div class="w-64 p-2">
            <div class="flex items-center gap-3 mb-3">
                <div class="avatar">
                    <div class="w-12 rounded-full">
                        <img src="https://example.com/avatar.jpg" alt="User Avatar" />
                    </div>
                </div>
                <div>
                    <h3 class="font-bold">John Doe</h3>
                    <p class="text-sm opacity-70">Administrator</p>
                </div>
            </div>
            <div class="border-t pt-2">
                <p class="mb-2">Last login: Today at 10:30 AM</p>
                <div class="flex gap-2">
                    <x-artisanpack-button size="sm">View Profile</x-artisanpack-button>
                    <x-artisanpack-button size="sm" color="ghost">Logout</x-artisanpack-button>
                </div>
            </div>
        </div>
    </x-slot:content>
</x-artisanpack-popover>
```

### Icon with Popover

```php
<x-artisanpack-popover>
    <x-slot:trigger>
        <x-artisanpack-icon name="o-information-circle" class="w-6 h-6 text-info cursor-pointer" />
    </x-slot:trigger>
    
    <x-slot:content>
        <p>This field is required and must be a valid email address.</p>
    </x-slot:content>
</x-artisanpack-popover>
```

### Custom Styled Popover

```php
<x-artisanpack-popover>
    <x-slot:trigger>
        <x-artisanpack-button>Hover Me</x-artisanpack-button>
    </x-slot:trigger>
    
    <x-slot:content class="bg-primary text-primary-content border-none">
        <p>This popover has custom styling.</p>
    </x-slot:content>
</x-artisanpack-popover>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the popover element |
| `position` | string\|null | `"bottom"` | Position of the popover relative to the trigger (`"top"`, `"right"`, `"bottom"`, `"left"`) |
| `offset` | string\|null | `"10"` | Distance in pixels between the trigger and the popover |

## Slots

| Slot | Description |
|------|-------------|
| `trigger` | The element that triggers the popover when hovered |
| `content` | The content to display in the popover |

## Behavior

The Popover component has the following behavior:

1. **Show on hover**: The popover appears when the user hovers over the trigger element
2. **Delayed hide**: The popover remains visible for a short time after the mouse leaves, allowing the user to move the cursor to the popover content
3. **Hover on content**: The popover remains visible when the user hovers over the popover content itself
4. **Automatic positioning**: The popover is automatically positioned relative to the trigger based on the specified position

## Styling

The Popover component uses a combination of Tailwind CSS and Alpine.js for styling and behavior. The popover content has a default style that can be customized.

### Default Classes

- `z-[1]` - Ensures the popover appears above other content
- `shadow-xl` - Adds a shadow for depth
- `border-[length:var(--border)] border-base-content/10` - Adds a subtle border
- `w-fit` - Sets the width to fit the content
- `p-3` - Adds padding around the content
- `rounded-md` - Rounds the corners
- `bg-base-100` - Sets the background color

You can customize the appearance of the popover content by adding classes to the `content` slot:

```php
<x-slot:content class="bg-secondary text-secondary-content">
    <!-- Content -->
</x-slot:content>
```

## Accessibility

The Popover component follows accessibility best practices:

- Uses proper hover interactions for showing/hiding content
- Provides sufficient contrast between the popover and the background
- Allows for keyboard navigation when interactive elements are inside the popover
- Maintains proper focus management

For better accessibility, consider the following:

1. Ensure the trigger element clearly indicates that additional information is available
2. Keep popover content concise and relevant to the trigger
3. For complex interactions, consider using a Modal component instead, which has more robust keyboard and focus management

## Related Components

- [Tooltip](tooltip) - Simpler version for short text hints
- [Dropdown](dropdown) - Click-triggered menu with options
- [Modal](modal) - Dialog box for more complex interactions