---
title: ListItem Component
---

# ListItem Component

The ListItem component provides a structured way to display items in a list format with support for avatars, content, and actions. It's ideal for user lists, notification feeds, message lists, and other data collections that benefit from a consistent layout.

## Basic Usage

```php
@php
$user = [
    'avatar' => 'https://example.com/avatar.jpg',
    'name' => 'John Doe',
    'email' => 'john@example.com'
];
@endphp

<x-artisanpack-list-item 
    :item="$user"
    value="name"
    subValue="email"
/>
```

## Examples

### Icon Examples

#### Basic List Icon with Icon Type

```php
<!-- Bullet point icon -->
<x-artisanpack-list-item 
    :item="$user"
    value="name"
    subValue="email"
    iconType="bullet"
/>

<!-- Checkmark icon -->
<x-artisanpack-list-item 
    :item="$task"
    value="title"
    subValue="description"
    iconType="checkmark"
/>

<!-- Arrow icon -->
<x-artisanpack-list-item 
    :item="$item"
    value="name"
    iconType="arrow"
/>
```

#### Status Icons

```php
<!-- Completed status -->
<x-artisanpack-list-item 
    :item="$task"
    value="title"
    subValue="description"
    iconStatus="completed"
/>

<!-- Error status -->
<x-artisanpack-list-item 
    :item="$errorItem"
    value="message"
    iconStatus="error"
/>

<!-- New status -->
<x-artisanpack-list-item 
    :item="$notification"
    value="title"
    subValue="time"
    iconStatus="new"
/>
```

#### Custom Icons

```php
<!-- Custom Heroicon -->
<x-artisanpack-list-item 
    :item="$file"
    value="name"
    subValue="size"
    icon="heroicon-o-document-text"
/>

<!-- Custom icon with size -->
<x-artisanpack-list-item 
    :item="$user"
    value="name"
    subValue="email"
    icon="heroicon-s-user-circle"
    iconClass="w-6 h-6 text-primary"
/>
```

#### Custom Icon Slot

```php
<x-artisanpack-list-item :item="$item" value="name" subValue="description">
    <x-slot:iconSlot>
        <div class="w-4 h-4 bg-primary rounded-full"></div>
    </x-slot:iconSlot>
</x-artisanpack-list-item>

<!-- Custom SVG icon -->
<x-artisanpack-list-item :item="$item" value="name">
    <x-slot:iconSlot>
        <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
        </svg>
    </x-slot:iconSlot>
</x-artisanpack-list-item>
```

#### Disabled Icons

```php
<!-- Disable icon completely -->
<x-artisanpack-list-item 
    :item="$user"
    value="name"
    subValue="email"
    :noIcon="true"
/>
```

### Basic List Item

```php
@php
$user = [
    'avatar' => 'https://example.com/avatar.jpg',
    'name' => 'John Doe',
    'email' => 'john@example.com'
];
@endphp

<x-artisanpack-list-item 
    :item="$user"
    value="name"
    subValue="email"
/>
```

### List Item with Link

```php
<x-artisanpack-list-item 
    :item="$user"
    value="name"
    subValue="email"
    link="/users/{{ $user['id'] }}"
/>
```

### List Item without Separator

```php
<x-artisanpack-list-item 
    :item="$user"
    value="name"
    subValue="email"
    :noSeparator="true"
/>
```

### List Item without Hover Effect

```php
<x-artisanpack-list-item 
    :item="$user"
    value="name"
    subValue="email"
    :noHover="true"
/>
```

### List Item with Actions

```php
<x-artisanpack-list-item 
    :item="$user"
    value="name"
    subValue="email"
>
    <x-slot:actions>
        <x-artisanpack-button icon="heroicon-o-pencil" class="btn-ghost btn-sm" wire:click="edit({{ $user['id'] }})" />
        <x-artisanpack-button icon="heroicon-o-trash" class="btn-ghost btn-sm text-error" wire:click="delete({{ $user['id'] }})" />
    </x-slot:actions>
</x-artisanpack-list-item>
```

### List Item with Custom Avatar

```php
<x-artisanpack-list-item 
    :item="$user"
    value="name"
    subValue="email"
>
    <x-slot:avatar>
        <div class="w-11 h-11 rounded-full bg-primary flex items-center justify-center text-white font-bold">
            {{ substr($user['name'], 0, 1) }}
        </div>
    </x-slot:avatar>
</x-artisanpack-list-item>
```

### List Item with Custom Content

```php
<x-artisanpack-list-item 
    :item="$user"
    avatar="avatar"
>
    <x-slot:value>
        <span class="text-primary">{{ $user['name'] }}</span>
    </x-slot:value>
    
    <x-slot:subValue>
        <div class="flex items-center gap-1">
            <x-artisanpack-icon name="heroicon-o-envelope" class="w-4 h-4" />
            <span>{{ $user['email'] }}</span>
        </div>
    </x-slot:subValue>
</x-artisanpack-list-item>
```

### List Item in a List

```php
<div class="rounded-box border border-base-content/10">
    @foreach($users as $user)
        <x-artisanpack-list-item 
            :item="$user"
            value="name"
            subValue="email"
            link="/users/{{ $user['id'] }}"
        >
            <x-slot:actions>
                <x-artisanpack-button icon="heroicon-o-ellipsis-vertical" class="btn-ghost btn-sm" />
            </x-slot:actions>
        </x-artisanpack-list-item>
    @endforeach
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `item` | object\|array | - | The data item to display (required) |
| `id` | string\|null | `null` | Optional ID for the list item element |
| `avatar` | string | `'avatar'` | Property name for the avatar image URL or slot for custom avatar content |
| `value` | string | `'name'` | Property name for the main content or slot for custom main content |
| `subValue` | string\|null | `''` | Property name for the secondary content or slot for custom secondary content |
| `noSeparator` | boolean\|null | `false` | Whether to hide the separator line |
| `noHover` | boolean\|null | `false` | Whether to disable the hover effect |
| `link` | string\|null | `null` | Optional URL for making the item clickable |
| `icon` | string\|null | `null` | Custom icon name (e.g., 'heroicon-o-user') |
| `iconType` | string\|null | `null` | Predefined icon type ('bullet', 'checkmark', 'arrow', 'dot') |
| `iconStatus` | string\|null | `null` | Status icon type ('new', 'completed', 'error', 'warning', 'info') |
| `iconClass` | string | `'w-4 h-4'` | CSS classes for the icon |
| `noIcon` | boolean\|null | `false` | Whether to disable icon display completely |

## Slots

| Slot | Description |
|------|-------------|
| `avatar` | Custom content for the avatar section |
| `value` | Custom content for the main content section |
| `subValue` | Custom content for the secondary content section |
| `actions` | Content for the actions section (typically buttons or links) |
| `iconSlot` | Custom content for the icon section (overrides icon props) |

## Styling

The ListItem component uses a combination of Tailwind CSS and DaisyUI for styling. It provides a consistent layout with proper spacing and alignment.

### Default Classes

- `flex justify-start items-center gap-4 px-3` - Base layout for the list item
- `hover:bg-base-200` - Hover effect (when not disabled)
- `cursor-pointer` - Applied when a link is provided
- `avatar` - DaisyUI avatar container
- `w-11 rounded-full` - Avatar size and shape
- `font-semibold truncate` - Main content styling
- `text-base-content/50 text-sm truncate` - Secondary content styling
- `border-t-[length:var(--border)] border-base-content/10` - Separator styling

## Accessibility

The ListItem component follows accessibility best practices:

- Uses semantic HTML for proper structure
- Provides proper link handling for navigation
- Maintains text truncation with ellipsis for long content
- Includes hover states for interactive elements
- Supports keyboard navigation when links are used

## Configuration

You can customize the default icons used by the ListItem component by publishing and modifying the configuration file:

```bash
php artisan vendor:publish --tag=artisanpack-config
```

Then edit `config/livewire-ui-components.php`:

```php
'icons' => [
    'list_item' => [
        // Default list style icons
        'bullet' => 'heroicon-o-list-bullet',
        'checkmark' => 'heroicon-o-check',
        'arrow' => 'heroicon-o-chevron-right',
        'dot' => 'heroicon-o-ellipsis-horizontal',
        
        // Status icons
        'status' => [
            'new' => 'heroicon-o-sparkles',
            'completed' => 'heroicon-o-check-circle',
            'error' => 'heroicon-o-exclamation-triangle',
            'warning' => 'heroicon-o-exclamation-circle',
            'info' => 'heroicon-o-information-circle',
        ],
        
        // Type icons
        'type' => [
            'user' => 'heroicon-o-user',
            'file' => 'heroicon-o-document',
            'folder' => 'heroicon-o-folder',
            'email' => 'heroicon-o-envelope',
            'notification' => 'heroicon-o-bell',
        ]
    ],
]
```

### Custom Icon Examples

```php
// Use a different bullet icon
'bullet' => 'heroicon-s-minus',

// Use custom icons for status
'status' => [
    'completed' => 'heroicon-s-check-circle',
    'error' => 'heroicon-s-x-circle',
],

// Add custom type icons
'type' => [
    'task' => 'heroicon-o-clipboard-document-list',
    'project' => 'heroicon-o-briefcase',
]
```

## Related Components

- [Avatar](avatar.md) - User avatar display
- [Button](button.md) - Used in the actions slot
- [Icon](icon.md) - Can be used in custom content