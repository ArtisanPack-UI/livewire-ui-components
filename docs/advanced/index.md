# Advanced Topics

This section covers advanced usage patterns and customization options for ArtisanPack UI Livewire Components.

## Overview

ArtisanPack UI Livewire Components is designed to be highly customizable and extensible. This section provides guidance on advanced usage patterns, customization techniques, and integration strategies.

## Topics Covered

### [Component Prefixing](component-prefixing.md)

Learn how to use component prefixing to avoid naming collisions with other packages or your own components. This is especially useful when integrating ArtisanPack UI with existing UI libraries or starter kits like Jetstream or Breeze.

Topics covered:
- Understanding component prefixing
- Configuring prefixes
- When to use prefixing
- Handling prefix changes

### [Custom Components](custom-components.md)

Discover how to create your own custom components that integrate with ArtisanPack UI. This includes extending existing components, creating wrapper components, and building entirely new components that follow the same design patterns.

Topics covered:
- Extending existing components
- Creating wrapper components
- Building new components
- Component composition patterns
- Best practices for component development

## Advanced Usage Patterns

### Component Composition

ArtisanPack UI components are designed to work together seamlessly. You can compose complex UI elements by combining multiple components:

```php
<x-artisanpack-card>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold">User Management</h3>
            <x-artisanpack-button size="sm" @click="showModal = true">
                <x-artisanpack-icon name="heroicon-o-plus" class="w-4 h-4 mr-1" />
                Add User
            </x-artisanpack-button>
        </div>
    </x-slot:header>
    
    <x-artisanpack-table :headers="['Name', 'Email', 'Role', 'Actions']">
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    <x-artisanpack-button size="xs" @click="editUser({{ $user->id }})">
                        Edit
                    </x-artisanpack-button>
                </td>
            </tr>
        @endforeach
        
        <x-slot:pagination>
            {{ $users->links() }}
        </x-slot:pagination>
    </x-artisanpack-table>
</x-artisanpack-card>

<x-artisanpack-modal title="Add User" x-show="showModal" @close="showModal = false">
    <x-artisanpack-form wire:submit="saveUser">
        <x-artisanpack-input label="Name" wire:model="name" required />
        <x-artisanpack-input label="Email" wire:model="email" type="email" required />
        <x-artisanpack-select label="Role" wire:model="role" :options="$roles" required />
        
        <x-slot:actions>
            <x-artisanpack-button @click="showModal = false" color="ghost">
                Cancel
            </x-artisanpack-button>
            <x-artisanpack-button type="submit" color="primary">
                Save
            </x-artisanpack-button>
        </x-slot:actions>
    </x-artisanpack-form>
</x-artisanpack-modal>
```

### Dynamic Components

You can use Laravel's dynamic components feature to render components dynamically:

```php
@php
    $componentType = $field->type;
    $componentName = "artisanpack-{$componentType}";
@endphp

<x-dynamic-component 
    :component="$componentName" 
    :label="$field->label" 
    :name="$field->name" 
    :required="$field->required"
/>
```

This is useful when building form generators or dynamic UIs.

### Scoped Slots

ArtisanPack UI components support scoped slots, which allow you to pass data from the component back to the slot content:

```php
<x-artisanpack-menu>
    @foreach($menuItems as $item)
        <x-artisanpack-menu-item :href="$item->url" :active="$item->isActive">
            @scope('icon', $iconName)
                <x-artisanpack-icon :name="$iconName" class="w-5 h-5" />
            @endscope
            
            {{ $item->label }}
        </x-artisanpack-menu-item>
    @endforeach
</x-artisanpack-menu>
```

### Alpine.js Integration

ArtisanPack UI components work seamlessly with Alpine.js for client-side interactivity:

```php
<div x-data="{ open: false }">
    <x-artisanpack-button @click="open = !open">
        Toggle Dropdown
    </x-artisanpack-button>
    
    <div x-show="open" @click.away="open = false" class="mt-2">
        <x-artisanpack-card>
            Dropdown content here
        </x-artisanpack-card>
    </div>
</div>
```

### Livewire Integration

ArtisanPack UI components are designed to work seamlessly with Livewire:

```php
<div>
    <x-artisanpack-input 
        label="Search" 
        wire:model.debounce.300ms="search" 
        placeholder="Search users..."
    />
    
    <x-artisanpack-table :headers="['Name', 'Email', 'Role']">
        @foreach($users as $user)
            <tr wire:key="user-{{ $user->id }}">
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
            </tr>
        @endforeach
        
        <x-slot:pagination>
            {{ $users->links() }}
        </x-slot:pagination>
    </x-artisanpack-table>
</div>
```

## Performance Considerations

### Lazy Loading

For components that might not be immediately visible, consider using lazy loading with Alpine.js:

```php
<div x-data="{ showModal: false }">
    <x-artisanpack-button @click="showModal = true">
        Open Modal
    </x-artisanpack-button>
    
    <template x-if="showModal">
        <x-artisanpack-modal title="Large Modal" @close="showModal = false">
            <!-- Heavy content here -->
        </x-artisanpack-modal>
    </template>
</div>
```

### Reducing DOM Size

For large tables or lists, consider using pagination or infinite scrolling to reduce the DOM size:

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role']">
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
    
    <x-slot:pagination>
        {{ $users->links() }}
    </x-slot:pagination>
</x-artisanpack-table>
```

### Optimizing Livewire Updates

When using Livewire, optimize updates by using `wire:key` to help Livewire track elements efficiently:

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role']">
    @foreach($users as $user)
        <tr wire:key="user-{{ $user->id }}">
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
</x-artisanpack-table>
```

## Next Steps

Explore the detailed guides on [Component Prefixing](component-prefixing.md) and [Custom Components](custom-components.md) to learn more about advanced customization options.
