---
title: Profile Component
---

# Profile Component

The Profile component combines the Avatar and Dropdown components, creating a clickable user profile that opens a dropdown menu. The avatar image or placeholder serves as the trigger for the dropdown content.

## Basic Usage

### With Image

```blade
<x-artisanpack-profile image="/path/to/avatar.jpg" alt="User Name">
    <x-artisanpack-menu-item title="Profile" />
    <x-artisanpack-menu-item title="Settings" />
    <x-artisanpack-menu-separator />
    <x-artisanpack-menu-item title="Logout" />
</x-artisanpack-profile>
```

### With Placeholder

```blade
<x-artisanpack-profile placeholder="JD" alt="John Doe">
    <x-artisanpack-menu-item title="My Account" />
    <x-artisanpack-menu-item title="Preferences" />
    <x-artisanpack-menu-item title="Sign Out" />
</x-artisanpack-profile>
```

## Color System

### Predefined Variants

Use predefined color variants for avatar backgrounds:

```blade
<x-artisanpack-profile placeholder="JD" alt="John Doe" color="primary">
    <x-artisanpack-menu-item title="Profile" />
</x-artisanpack-profile>

<x-artisanpack-profile placeholder="SM" alt="Sarah Miller" color="secondary">
    <x-artisanpack-menu-item title="Settings" />
</x-artisanpack-profile>

<x-artisanpack-profile placeholder="AB" alt="Alex Brown" color="accent">
    <x-artisanpack-menu-item title="Account" />
</x-artisanpack-profile>
```

### Tailwind Color Palette

Use any Tailwind color with intensity levels:

```blade
<x-artisanpack-profile placeholder="JD" alt="John Doe" color="blue-500">
    <x-artisanpack-menu-item title="Profile" />
</x-artisanpack-profile>

<x-artisanpack-profile placeholder="SM" alt="Sarah Miller" color="purple-600">
    <x-artisanpack-menu-item title="Settings" />
</x-artisanpack-profile>
```

### Custom Hex Colors

Use custom hex color codes:

```blade
<x-artisanpack-profile placeholder="JD" alt="John Doe" color="#ff6b6b">
    <x-artisanpack-menu-item title="Profile" />
</x-artisanpack-profile>
```

### Color Adjustments

Fine-tune the background appearance:

```blade
<!-- Lighter background -->
<x-artisanpack-profile placeholder="JD" alt="John Doe" color="blue-500" color-adjustment="lighter">
    <x-artisanpack-menu-item title="Profile" />
</x-artisanpack-profile>

<!-- Darker background -->
<x-artisanpack-profile placeholder="SM" alt="Sarah Miller" color="blue-500" color-adjustment="darker">
    <x-artisanpack-menu-item title="Settings" />
</x-artisanpack-profile>
```

## With Title and Subtitle

Display additional information alongside the avatar:

```blade
<x-artisanpack-profile 
    image="/path/to/avatar.jpg" 
    alt="John Doe"
    title="John Doe"
    subtitle="Software Engineer"
>
    <x-artisanpack-menu-item title="View Profile" />
    <x-artisanpack-menu-item title="Edit Profile" />
    <x-artisanpack-menu-separator />
    <x-artisanpack-menu-item title="Account Settings" />
    <x-artisanpack-menu-item title="Billing" />
    <x-artisanpack-menu-separator />
    <x-artisanpack-menu-item title="Sign Out" />
</x-artisanpack-profile>
```

## Dropdown Positioning

### Right-aligned Dropdown

```blade
<x-artisanpack-profile 
    image="/path/to/avatar.jpg" 
    alt="User Name"
    right
>
    <x-artisanpack-menu-item title="Profile" />
    <x-artisanpack-menu-item title="Logout" />
</x-artisanpack-profile>
```

### Top-positioned Dropdown

```blade
<x-artisanpack-profile 
    image="/path/to/avatar.jpg" 
    alt="User Name"
    top
>
    <x-artisanpack-menu-item title="Profile" />
    <x-artisanpack-menu-item title="Logout" />
</x-artisanpack-profile>
```

### Native Dropdown Positioning

Use native CSS positioning instead of Alpine.js anchor:

```blade
<x-artisanpack-profile 
    image="/path/to/avatar.jpg" 
    alt="User Name"
    no-x-anchor
    right
>
    <x-artisanpack-menu-item title="Profile" />
    <x-artisanpack-menu-item title="Logout" />
</x-artisanpack-profile>
```

## Advanced Examples

### Complete User Menu

```blade
<x-artisanpack-profile 
    image="/path/to/user.jpg" 
    alt="Jane Smith"
    title="Jane Smith"
    subtitle="Product Manager"
    color="primary"
    right
>
    <div class="px-4 py-3 border-b border-base-content/10">
        <div class="font-semibold">jane.smith@company.com</div>
        <div class="text-sm text-base-content/70">Premium Plan</div>
    </div>
    
    <x-artisanpack-menu-item title="View Profile" icon="o-user" />
    <x-artisanpack-menu-item title="Account Settings" icon="o-cog-6-tooth" />
    <x-artisanpack-menu-item title="Billing" icon="o-credit-card" />
    
    <x-artisanpack-menu-separator />
    
    <x-artisanpack-menu-item title="Help & Support" icon="o-question-mark-circle" />
    <x-artisanpack-menu-item title="Keyboard Shortcuts" icon="o-command-line" />
    
    <x-artisanpack-menu-separator />
    
    <x-artisanpack-menu-item title="Sign Out" icon="o-arrow-right-on-rectangle" />
</x-artisanpack-profile>
```

### With Custom Dropdown Content

```blade
<x-artisanpack-profile 
    placeholder="JS" 
    alt="John Smith"
    color="accent"
>
    <div class="p-4">
        <div class="flex items-center gap-3 mb-4">
            <x-artisanpack-avatar 
                placeholder="JS" 
                alt="John Smith" 
                color="accent" 
                class="w-12"
            />
            <div>
                <div class="font-semibold">John Smith</div>
                <div class="text-sm text-base-content/70">john@example.com</div>
            </div>
        </div>
        
        <div class="space-y-2">
            <x-artisanpack-button label="Manage Account" variant="ghost" class="w-full justify-start" />
            <x-artisanpack-button label="Settings" variant="ghost" class="w-full justify-start" />
            <x-artisanpack-button label="Sign Out" variant="ghost" class="w-full justify-start" />
        </div>
    </div>
</x-artisanpack-profile>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the profile component |
| image | string | '' | URL of the avatar image |
| alt | string | '' | Alternative text for the avatar image |
| placeholder | string | '' | Text to display when no image is provided |
| title | string | null | Title text displayed beside the avatar |
| subtitle | string | null | Subtitle text displayed beside the avatar |
| color | string | null | Color variant, Tailwind color (e.g., 'red-500'), or hex code (e.g., '#ff0000') |
| color-adjustment | string | null | Background adjustment: 'lighter', 'darker', 'transparent', or 'subtle' |
| right | boolean | false | Position dropdown to the right |
| top | boolean | false | Position dropdown above the trigger |
| no-x-anchor | boolean | false | Use native dropdown positioning instead of Alpine.js anchor |

## Slots

| Name | Description |
|------|-------------|
| default | Content to display in the dropdown menu |

## CSS Classes

The component accepts all standard HTML attributes, including CSS classes. The profile component combines the styling of both Avatar and Dropdown components:

- The avatar portion uses the same classes as the standalone Avatar component
- The dropdown portion follows the same styling patterns as the Dropdown component
- Additional classes can be applied to customize the appearance

## Accessibility

The Profile component maintains proper accessibility features:

- The avatar trigger is keyboard navigable
- Screen readers can access the alt text and dropdown content
- The dropdown closes when clicking outside or pressing Escape
- Proper ARIA attributes are maintained for dropdown functionality

## Notes

- The Profile component uses Alpine.js for dropdown state management
- The dropdown automatically closes when clicking outside the component
- The component combines all the features of both Avatar and Dropdown components
- Menu items and separators can be used within the dropdown content for consistent styling