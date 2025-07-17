# Tabs Component

The Tabs component provides a way to organize content into multiple sections that can be displayed one at a time. It's ideal for interfaces where users need to switch between related content without navigating to a different page.

## Basic Usage

```php
<x-artisanpack-tabs>
    <x-artisanpack-tab name="tab1" label="Tab 1" active>
        Content for Tab 1
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="tab2" label="Tab 2">
        Content for Tab 2
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="tab3" label="Tab 3">
        Content for Tab 3
    </x-artisanpack-tab>
</x-artisanpack-tabs>
```

## Examples

### Basic Tabs

```php
<x-artisanpack-tabs>
    <x-artisanpack-tab name="profile" label="Profile" active>
        <div class="p-4">
            <h3 class="text-lg font-bold">User Profile</h3>
            <p>This is the profile content.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="settings" label="Settings">
        <div class="p-4">
            <h3 class="text-lg font-bold">User Settings</h3>
            <p>This is the settings content.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="notifications" label="Notifications">
        <div class="p-4">
            <h3 class="text-lg font-bold">Notifications</h3>
            <p>This is the notifications content.</p>
        </div>
    </x-artisanpack-tab>
</x-artisanpack-tabs>
```

### Tabs with Different Styles

```php
<!-- Default Tabs (Bordered) -->
<x-artisanpack-tabs>
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>

<!-- Lifted Tabs -->
<x-artisanpack-tabs style="lifted">
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>

<!-- Boxed Tabs -->
<x-artisanpack-tabs style="boxed">
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>
```

### Tabs with Custom Sizes

```php
<!-- Extra Small Tabs -->
<x-artisanpack-tabs size="xs">
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>

<!-- Small Tabs -->
<x-artisanpack-tabs size="sm">
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>

<!-- Medium Tabs (Default) -->
<x-artisanpack-tabs size="md">
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>

<!-- Large Tabs -->
<x-artisanpack-tabs size="lg">
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>
```

### Tabs with Custom Colors

```php
<x-artisanpack-tabs class="tabs-primary">
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>

<x-artisanpack-tabs class="tabs-secondary">
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>

<x-artisanpack-tabs class="tabs-accent">
    <x-artisanpack-tab name="tab1" label="Tab 1" active>Content 1</x-artisanpack-tab>
    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
    <x-artisanpack-tab name="tab3" label="Tab 3">Content 3</x-artisanpack-tab>
</x-artisanpack-tabs>
```

### Tabs with Livewire Integration

```php
<x-artisanpack-tabs wire:model="activeTab">
    <x-artisanpack-tab name="profile" label="Profile">
        <div class="p-4">
            <h3 class="text-lg font-bold">User Profile</h3>
            <p>This is the profile content.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="settings" label="Settings">
        <div class="p-4">
            <h3 class="text-lg font-bold">User Settings</h3>
            <p>This is the settings content.</p>
        </div>
    </x-artisanpack-tab>
</x-artisanpack-tabs>
```

### Tabs with Alpine.js Integration

```php
<div x-data="{ activeTab: 'profile' }">
    <x-artisanpack-tabs x-model="activeTab">
        <x-artisanpack-tab name="profile" label="Profile">
            <div class="p-4">
                <h3 class="text-lg font-bold">User Profile</h3>
                <p>This is the profile content.</p>
            </div>
        </x-artisanpack-tab>
        
        <x-artisanpack-tab name="settings" label="Settings">
            <div class="p-4">
                <h3 class="text-lg font-bold">User Settings</h3>
                <p>This is the settings content.</p>
            </div>
        </x-artisanpack-tab>
    </x-artisanpack-tabs>
    
    <div class="mt-4">
        <button @click="activeTab = 'profile'" class="btn btn-sm">Show Profile</button>
        <button @click="activeTab = 'settings'" class="btn btn-sm">Show Settings</button>
    </div>
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `style` | string | `null` | Tab style (`null` for default, `lifted`, `boxed`) |
| `size` | string | `md` | Size of the tabs (`xs`, `sm`, `md`, `lg`) |
| `id` | string | `null` | Optional ID for the tabs container |
| `remember` | boolean | `false` | Whether to remember the active tab in local storage |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Contains the Tab components that make up the tabbed interface |

## Behavior

The Tabs component:

1. Manages a collection of Tab components
2. Handles tab selection and content display
3. Shows only the content of the active tab
4. Can remember the active tab between page loads when the `remember` prop is used
5. Can be controlled programmatically through Livewire or Alpine.js

## Styling

The Tabs component uses DaisyUI's tabs component under the hood, which provides a clean and consistent appearance. You can customize the appearance by:

1. Using the provided props (`style`, `size`)
2. Adding custom classes via the `class` attribute
3. Using DaisyUI's color classes (e.g., `tabs-primary`, `tabs-secondary`)

### Default Classes

- Tabs are displayed in a row with appropriate spacing
- The active tab is visually distinguished from inactive tabs
- Different styles (default, lifted, boxed) provide different visual appearances
- Different sizes adjust the padding and font size of the tabs

## Accessibility

The Tabs component follows accessibility best practices:

- Uses appropriate ARIA attributes (`role="tablist"`, `role="tab"`, `role="tabpanel"`, etc.)
- Maintains proper focus management
- Supports keyboard navigation (arrow keys, Tab key)

For better accessibility:

1. Provide clear, descriptive labels for each tab
2. Ensure sufficient color contrast for tab labels in all states
3. Make sure tab content is properly structured with headings and semantic HTML
4. Consider users who navigate with keyboards when designing tab interactions

## Related Components

- [Tab](tab.md) - Individual tab component used within Tabs
- [Accordion](accordion.md) - Alternative way to organize content in collapsible sections
- [Card](card.md) - Container for content with similar styling