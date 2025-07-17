# Avatar Component

The Avatar component displays a user's profile picture with optional title and subtitle text.

## Basic Usage

```blade
<x-artisanpack-avatar image="/path/to/image.jpg" alt="User Name" />
```

## With Placeholder

```blade
<x-artisanpack-avatar placeholder="JD" alt="John Doe" />
```

## With Title and Subtitle

```blade
<x-artisanpack-avatar 
    image="/path/to/image.jpg" 
    alt="John Doe"
    title="John Doe"
    subtitle="Software Engineer"
/>
```

## With Slot Content

```blade
<x-artisanpack-avatar image="/path/to/image.jpg" alt="John Doe">
    <x-slot:title>
        <div class="flex items-center gap-2">
            <span>John Doe</span>
            <x-artisanpack-badge value="Pro" class="badge-primary badge-sm" />
        </div>
    </x-slot:title>
    
    <x-slot:subtitle>
        <div class="flex items-center gap-2">
            <x-artisanpack-icon name="o-envelope" class="w-3 h-3" />
            <span>john.doe@example.com</span>
        </div>
    </x-slot:subtitle>
</x-artisanpack-avatar>
```

## Custom Size

```blade
<x-artisanpack-avatar 
    image="/path/to/image.jpg" 
    alt="John Doe"
    class="w-12 rounded-full"
/>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the avatar |
| image | string | '' | URL of the avatar image |
| alt | string | '' | Alternative text for the avatar image |
| placeholder | string | '' | Text to display when no image is provided |
| title | string | null | Title text displayed beside the avatar |
| subtitle | string | null | Subtitle text displayed beside the avatar |

## Slots

| Name | Description |
|------|-------------|
| title | Custom content for the title area |
| subtitle | Custom content for the subtitle area |

## CSS Classes

The component accepts all standard HTML attributes, including CSS classes. By default, the avatar image is displayed with a size of `w-7` and a `rounded-full` shape. You can override these by passing your own classes to the component.
