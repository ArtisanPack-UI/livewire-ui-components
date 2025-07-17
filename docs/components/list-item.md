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
        <x-artisanpack-button icon="o-pencil" class="btn-ghost btn-sm" wire:click="edit({{ $user['id'] }})" />
        <x-artisanpack-button icon="o-trash" class="btn-ghost btn-sm text-error" wire:click="delete({{ $user['id'] }})" />
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
            <x-artisanpack-icon name="o-envelope" class="w-4 h-4" />
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
                <x-artisanpack-button icon="o-ellipsis-vertical" class="btn-ghost btn-sm" />
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

## Slots

| Slot | Description |
|------|-------------|
| `avatar` | Custom content for the avatar section |
| `value` | Custom content for the main content section |
| `subValue` | Custom content for the secondary content section |
| `actions` | Content for the actions section (typically buttons or links) |

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

## Related Components

- [Avatar](avatar.md) - User avatar display
- [Button](button.md) - Used in the actions slot
- [Icon](icon.md) - Can be used in custom content