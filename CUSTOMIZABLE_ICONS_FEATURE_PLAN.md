# Customizable Icons Feature Plan

## Overview

This document outlines the plan to add customizable icon support for the ListItem and Loading components in the ArtisanPack UI Livewire UI Components package. The feature will allow developers to customize icons using either the Blade Icons package (Heroicons and other icon sets) or custom SVG content.

## Current State Analysis

### Existing Infrastructure

1. **Icon Component**: Already exists and integrates with Blade Icons package
   - Renames BladeUI Icons `<x-icon>` to `<x-svg>` to avoid collisions
   - Supports Heroicons with prefixes (`o-`, `s-`, `m-`)
   - Supports custom icons with dot notation
   - Default size: `w-5 h-5`

2. **ListItem Component**: 
   - Currently has no built-in list-style icons
   - Supports icons in action slots and custom content slots
   - Uses avatar system for visual elements

3. **Loading Component**:
   - Uses DaisyUI CSS classes for animations (`loading-spinner`, `loading-dots`, etc.)
   - Currently CSS-based, not SVG-based

4. **Configuration System**:
   - Uses `config('livewire-ui-components.prefix')` for component prefixes
   - Service provider registers components with configurable prefix

## Feature Requirements

### ListItem Component Icon Customization

The ListItem component should support customizable icons for:

1. **List Style Icons**: Optional icons that appear before the content (bullets, checkmarks, arrows, etc.)
2. **Status Icons**: Icons that indicate item status (new, completed, error, etc.)
3. **Type Icons**: Icons that indicate item type (user, file, folder, etc.)

### Loading Component Icon Customization

The Loading component should support:

1. **Custom SVG Spinners**: Replace CSS-based animations with customizable SVG icons
2. **Static Loading Icons**: Support for non-animated loading icons
3. **Backward Compatibility**: Maintain existing DaisyUI CSS-based approach as default

## Architecture Design

### 1. Configuration Structure

Create a new configuration file: `config/livewire-ui-components.php`

```php
<?php

return [
    'prefix' => '',
    
    'icons' => [
        'list_item' => [
            // Default list style icons
            'bullet' => 'o-minus',
            'checkmark' => 'o-check',
            'arrow' => 'o-chevron-right',
            'dot' => 'o-ellipsis-horizontal',
            
            // Status icons
            'status' => [
                'new' => 'o-sparkles',
                'completed' => 'o-check-circle',
                'error' => 'o-exclamation-triangle',
                'warning' => 'o-exclamation-circle',
                'info' => 'o-information-circle',
            ],
            
            // Type icons
            'type' => [
                'user' => 'o-user',
                'file' => 'o-document',
                'folder' => 'o-folder',
                'email' => 'o-envelope',
                'notification' => 'o-bell',
            ]
        ],
        
        'loading' => [
            // Custom loading icons (SVG-based)
            'spinner' => 'o-arrow-path',
            'dots' => null, // Keep CSS-based
            'ring' => null, // Keep CSS-based
            'custom_svg' => null, // Allow custom SVG content
            
            // Default loading type
            'default_type' => 'css', // 'css' or 'svg'
        ]
    ]
];
```

### 2. ListItem Component Enhancement

#### New Props

```php
public function __construct(
    // Existing props...
    public object|array $item,
    public ?string $id = null,
    public string $avatar = 'avatar',
    public string $value = 'name',
    public ?string $subValue = '',
    public ?bool $noSeparator = false,
    public ?bool $noHover = false,
    public ?string $link = null,
    
    // New icon props
    public ?string $icon = null,           // Custom icon name
    public ?string $iconType = null,       // 'bullet', 'checkmark', 'arrow', 'dot'
    public ?string $iconStatus = null,     // 'new', 'completed', 'error', 'warning', 'info'
    public ?string $iconClass = 'w-4 h-4', // Icon classes
    public ?bool $noIcon = false,          // Disable icon completely
    
    // Slots
    public mixed $actions = null,
    public mixed $iconSlot = null,         // Custom icon slot
) {
    // Constructor logic...
}
```

#### New Methods

```php
public function getIcon(): ?string
{
    // Priority: custom icon > iconType > iconStatus > null
    if ($this->icon) {
        return $this->icon;
    }
    
    if ($this->iconType) {
        return config("livewire-ui-components.icons.list_item.{$this->iconType}");
    }
    
    if ($this->iconStatus) {
        return config("livewire-ui-components.icons.list_item.status.{$this->iconStatus}");
    }
    
    return null;
}

public function shouldShowIcon(): bool
{
    return !$this->noIcon && ($this->getIcon() || $this->iconSlot);
}
```

#### Blade Template Updates

```blade
<div wire:key="{{ $uuid }}">
    <div {{ $attributes->class([
            "flex justify-start items-center gap-4 px-3",
            "hover:bg-base-200" => !$noHover,
            "cursor-pointer" => $link
        ]) }}>

        @if($link && ($shouldShowIcon() || data_get($item, $avatar) || !is_string($avatar)))
            <div>
                <a href="{{ $link }}" wire:navigate>
        @endif

        <!-- ICON (NEW) -->
        @if($shouldShowIcon())
            <div class="py-3 flex-shrink-0">
                @if($iconSlot)
                    {{ $iconSlot }}
                @elseif($getIcon())
                    <x-svg :name="$getIcon()" {{ $attributes->only('class')->class($iconClass) }} />
                @endif
            </div>
        @endif

        <!-- AVATAR (EXISTING) -->
        @if(data_get($item, $avatar))
            <div class="py-3">
                <div class="avatar">
                    <div class="w-11 rounded-full">
                        <img src="{{ data_get($item, $avatar) }}" />
                    </div>
                </div>
            </div>
        @endif

        @if(!is_string($avatar))
            <div {{ $avatar->attributes->class(["py-3"]) }}>
                {{ $avatar }}
            </div>
        @endif

        <!-- ... rest of existing template ... -->
    </div>
</div>
```

### 3. Loading Component Enhancement

#### New Props

```php
public function __construct(
    public ?string $id = null,
    
    // New props
    public ?string $type = null,           // 'css', 'svg', 'custom'
    public ?string $icon = null,           // Custom icon name for SVG type
    public ?string $customSvg = null,      // Custom SVG content
    public ?bool $animated = true,         // Enable/disable animation
) {
    // Constructor logic...
}
```

#### New Methods

```php
public function getLoadingType(): string
{
    if ($this->type) {
        return $this->type;
    }
    
    return config('livewire-ui-components.icons.loading.default_type', 'css');
}

public function getLoadingIcon(): ?string
{
    if ($this->icon) {
        return $this->icon;
    }
    
    return config('livewire-ui-components.icons.loading.spinner');
}

public function shouldUseSvg(): bool
{
    return $this->getLoadingType() === 'svg' || $this->icon || $this->customSvg;
}
```

#### Blade Template Updates

```blade
@if($shouldUseSvg())
    @if($customSvg)
        {!! $customSvg !!}
    @elseif($getLoadingIcon())
        <x-svg 
            :name="$getLoadingIcon()" 
            {{ $attributes->class(['inline', 'w-5 h-5' => !Str::contains($attributes->get('class') ?? '', ['w-', 'h-'])]) }}
            @if($animated) style="animation: spin 1s linear infinite;" @endif
        />
    @else
        <span {{ $attributes->class("loading loading-spinner") }}></span>
    @endif
@else
    <span {{ $attributes->class("loading") }}></span>
@endif
```

## Implementation Plan

### Phase 1: Configuration and Infrastructure
1. Create configuration file with default icon mappings
2. Update service provider to publish configuration
3. Add configuration loading to existing components

### Phase 2: ListItem Component Enhancement
1. Add new props to ListItem PHP class
2. Implement icon resolution methods
3. Update Blade template with icon support
4. Add icon slot support
5. Update documentation with examples

### Phase 3: Loading Component Enhancement
1. Add new props to Loading PHP class
2. Implement SVG/CSS type resolution
3. Update Blade template with conditional rendering
4. Add CSS for SVG animations
5. Update documentation with examples

### Phase 4: Testing and Documentation
1. Create comprehensive tests for both components
2. Update existing tests to ensure backward compatibility
3. Create migration guide for existing users
4. Update component documentation
5. Add examples to documentation site

## Backward Compatibility

### ListItem Component
- All existing functionality remains unchanged
- Icons are opt-in via new props
- No breaking changes to existing API

### Loading Component
- Default behavior remains CSS-based (DaisyUI)
- SVG support is opt-in via new props
- Existing class-based customization still works

## Usage Examples

### ListItem with Icons

```php
<!-- Basic list icon -->
<x-artisanpack-list-item 
    :item="$user" 
    value="name" 
    subValue="email"
    iconType="bullet"
/>

<!-- Status icon -->
<x-artisanpack-list-item 
    :item="$task" 
    value="title" 
    subValue="description"
    iconStatus="completed"
/>

<!-- Custom icon -->
<x-artisanpack-list-item 
    :item="$file" 
    value="name" 
    subValue="size"
    icon="o-document-text"
/>

<!-- Custom icon slot -->
<x-artisanpack-list-item :item="$item" value="name">
    <x-slot:iconSlot>
        <div class="w-4 h-4 bg-primary rounded-full"></div>
    </x-slot:iconSlot>
</x-artisanpack-list-item>
```

### Loading with Custom Icons

```php
<!-- SVG-based loading -->
<x-artisanpack-loading type="svg" icon="o-arrow-path" />

<!-- Custom SVG -->
<x-artisanpack-loading 
    type="custom" 
    customSvg="<svg>...</svg>" 
    :animated="false" 
/>

<!-- CSS-based (default) -->
<x-artisanpack-loading class="loading-dots" />
```

## Configuration Customization

Users can customize icons in their `config/livewire-ui-components.php`:

```php
'icons' => [
    'list_item' => [
        'bullet' => 'custom.bullet-icon',
        'checkmark' => 's-check-circle',
        'status' => [
            'completed' => 'custom.success-icon',
        ]
    ],
    'loading' => [
        'spinner' => 'custom.loading-spinner',
        'default_type' => 'svg',
    ]
]
```

## Benefits

1. **Flexibility**: Developers can use any icon from Blade Icons or custom SVGs
2. **Consistency**: Centralized icon configuration ensures consistent iconography
3. **Performance**: SVG icons can be more performant than CSS animations
4. **Customization**: Easy to override default icons per project needs
5. **Accessibility**: SVG icons can include accessibility attributes
6. **Backward Compatibility**: Existing code continues to work unchanged

## Potential Challenges

1. **Bundle Size**: Including many SVG icons might increase bundle size
2. **Animation Complexity**: SVG animations might be more complex than CSS
3. **Browser Support**: Ensure SVG animations work across target browsers
4. **Configuration Complexity**: Balance between flexibility and simplicity

## Future Enhancements

1. **Icon Library Integration**: Support for additional icon libraries (Phosphor, Lucide, etc.)
2. **Animation Presets**: Pre-defined animation styles for SVG icons
3. **Icon Caching**: Cache compiled SVG icons for better performance
4. **Theme Integration**: Icon variations based on light/dark theme
5. **Icon Size Presets**: Predefined size classes for common use cases

This feature will significantly enhance the flexibility and customization options for the ListItem and Loading components while maintaining backward compatibility and following Laravel/Blade conventions.