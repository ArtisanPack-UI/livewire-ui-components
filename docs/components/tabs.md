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

### Vertical Tabs

The tabs component supports vertical orientations, allowing tab navigation to be positioned on the left or right side of the content.

#### Vertical Left Tabs

```php
<x-artisanpack-tabs orientation="vertical-left">
    <x-artisanpack-tab name="dashboard" label="Dashboard">
        <div class="p-4">
            <h3 class="text-lg font-bold">Dashboard</h3>
            <p>Main dashboard content with charts and widgets.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="analytics" label="Analytics">
        <div class="p-4">
            <h3 class="text-lg font-bold">Analytics</h3>
            <p>Analytics and reporting data.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="reports" label="Reports">
        <div class="p-4">
            <h3 class="text-lg font-bold">Reports</h3>
            <p>Generated reports and exports.</p>
        </div>
    </x-artisanpack-tab>
</x-artisanpack-tabs>
```

#### Vertical Right Tabs

```php
<x-artisanpack-tabs orientation="vertical-right">
    <x-artisanpack-tab name="messages" label="Messages">
        <div class="p-4">
            <h3 class="text-lg font-bold">Messages</h3>
            <p>Your messages and conversations.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="notifications" label="Notifications">
        <div class="p-4">
            <h3 class="text-lg font-bold">Notifications</h3>
            <p>Recent notifications and alerts.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="history" label="History">
        <div class="p-4">
            <h3 class="text-lg font-bold">History</h3>
            <p>Activity history and logs.</p>
        </div>
    </x-artisanpack-tab>
</x-artisanpack-tabs>
```

#### Vertical Tabs with Custom Styling

```php
<!-- Custom vertical-left tabs -->
<x-artisanpack-tabs 
    orientation="vertical-left"
    vertical-tabs-class="relative w-full flex flex-col lg:flex-row"
    vertical-label-class="font-bold px-4 py-3"
    vertical-active-class="bg-primary text-primary-content border-r-4 border-primary"
    vertical-label-div-class="border-r-2 border-base-300 flex flex-col min-w-60">
    
    <x-artisanpack-tab name="tab1" label="Custom Tab 1">
        <div class="p-6">
            <h3 class="text-xl font-bold">Custom Styled Content</h3>
            <p>This tab uses custom styling classes.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="tab2" label="Custom Tab 2">
        <div class="p-6">
            <h3 class="text-xl font-bold">Another Custom Tab</h3>
            <p>Each tab can have different content and styling.</p>
        </div>
    </x-artisanpack-tab>
</x-artisanpack-tabs>
```

#### Responsive Vertical Tabs

Vertical tabs automatically adapt to mobile devices, stacking vertically on small screens and displaying horizontally on larger screens.

```php
<div class="h-96"> <!-- Fixed height container recommended for vertical tabs -->
    <x-artisanpack-tabs orientation="vertical-left">
        <x-artisanpack-tab name="mobile-tab1" label="Mobile Friendly">
            <div class="p-4">
                <h3 class="text-lg font-bold">Responsive Design</h3>
                <p>On mobile: tabs display horizontally at the top.</p>
                <p>On desktop: tabs display vertically on the left.</p>
            </div>
        </x-artisanpack-tab>
        
        <x-artisanpack-tab name="mobile-tab2" label="Adaptive Layout">
            <div class="p-4">
                <h3 class="text-lg font-bold">Adaptive Behavior</h3>
                <p>The layout automatically adjusts based on screen size.</p>
            </div>
        </x-artisanpack-tab>
    </x-artisanpack-tabs>
</div>
```

#### Vertical Tabs with Icons

```php
<x-artisanpack-tabs orientation="vertical-left">
    <x-artisanpack-tab name="home" label="Home" icon="home">
        <div class="p-4">
            <h3 class="text-lg font-bold">Home</h3>
            <p>Welcome to the home page.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="settings" label="Settings" icon="cog">
        <div class="p-4">
            <h3 class="text-lg font-bold">Settings</h3>
            <p>Manage your preferences.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="profile" label="Profile" icon="user">
        <div class="p-4">
            <h3 class="text-lg font-bold">Profile</h3>
            <p>View and edit your profile.</p>
        </div>
    </x-artisanpack-tab>
</x-artisanpack-tabs>
```

## Props

### Basic Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string | `null` | Optional ID for the tabs container |
| `selected` | string | `null` | Pre-selected tab name |
| `orientation` | string | `horizontal` | Tab orientation (`horizontal`, `vertical-left`, `vertical-right`) |

### Horizontal Layout Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `labelClass` | string | `font-semibold pb-1` | CSS classes for horizontal tab labels |
| `activeClass` | string | `border-b-[length:var(--border)] border-b-base-content/50` | CSS classes for active horizontal tabs |
| `labelDivClass` | string | `border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto` | CSS classes for horizontal label container |
| `tabsClass` | string | `relative w-full` | CSS classes for horizontal tabs container |

### Vertical Layout Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `verticalTabsClass` | string | `relative w-full flex flex-col md:flex-row` | CSS classes for vertical tabs container |
| `verticalLabelClass` | string | `font-semibold px-3 py-2 md:pr-1 md:pl-1 md:py-2` | CSS classes for vertical tab labels |
| `verticalActiveClass` | string | `border-b-[length:var(--border)] border-b-base-content/50 md:border-b-0 md:border-r-[length:var(--border)] md:border-r-base-content/50` | CSS classes for active vertical-left tabs |
| `verticalLabelDivClass` | string | `border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto md:border-b-0 md:border-r-[length:var(--border)] md:border-r-base-content/10 md:flex-col md:overflow-y-auto md:min-w-48` | CSS classes for vertical-left label container |
| `verticalContentClass` | string | `flex-1 pt-4 md:pt-0` | CSS classes for vertical content area |

### Vertical Right Layout Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `verticalRightActiveClass` | string | `border-b-[length:var(--border)] border-b-base-content/50 md:border-b-0 md:border-l-[length:var(--border)] md:border-l-base-content/50` | CSS classes for active vertical-right tabs |
| `verticalRightLabelDivClass` | string | `border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto md:border-b-0 md:border-l-[length:var(--border)] md:border-l-base-content/10 md:flex-col md:overflow-y-auto md:min-w-48` | CSS classes for vertical-right label container |

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