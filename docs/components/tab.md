---
title: Tab Component
---

# Tab Component

The Tab component represents an individual tab within a tabbed interface. It's designed to be used within the Tabs component to create a complete tabbed navigation system.

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

### Basic Tab

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
</x-artisanpack-tabs>
```

### Tab with Icon

```php
<x-artisanpack-tabs>
    <x-artisanpack-tab name="profile" active>
        <x-slot:label>
            <div class="flex items-center">
                <x-artisanpack-icon name="heroicon-o-user" class="w-5 h-5 mr-2" />
                Profile
            </div>
        </x-slot:label>
        
        <div class="p-4">
            <h3 class="text-lg font-bold">User Profile</h3>
            <p>This is the profile content.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="settings">
        <x-slot:label>
            <div class="flex items-center">
                <x-artisanpack-icon name="heroicon-o-cog" class="w-5 h-5 mr-2" />
                Settings
            </div>
        </x-slot:label>
        
        <div class="p-4">
            <h3 class="text-lg font-bold">User Settings</h3>
            <p>This is the settings content.</p>
        </div>
    </x-artisanpack-tab>
</x-artisanpack-tabs>
```

### Tab with Badge

```php
<x-artisanpack-tabs>
    <x-artisanpack-tab name="inbox" active>
        <x-slot:label>
            <div class="flex items-center">
                Inbox
                <x-artisanpack-badge class="ml-2">3</x-artisanpack-badge>
            </div>
        </x-slot:label>
        
        <div class="p-4">
            <h3 class="text-lg font-bold">Inbox</h3>
            <p>You have 3 unread messages.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="sent">
        <x-slot:label>
            Sent
        </x-slot:label>
        
        <div class="p-4">
            <h3 class="text-lg font-bold">Sent Messages</h3>
            <p>Your sent messages appear here.</p>
        </div>
    </x-artisanpack-tab>
</x-artisanpack-tabs>
```

### Disabled Tab

```php
<x-artisanpack-tabs>
    <x-artisanpack-tab name="active" label="Active" active>
        <div class="p-4">
            <h3 class="text-lg font-bold">Active Tab</h3>
            <p>This tab is active and can be interacted with.</p>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="disabled" label="Disabled" disabled>
        <div class="p-4">
            <h3 class="text-lg font-bold">Disabled Tab</h3>
            <p>This content won't be shown because the tab is disabled.</p>
        </div>
    </x-artisanpack-tab>
</x-artisanpack-tabs>
```

### Tab with Custom Content

```php
<x-artisanpack-tabs>
    <x-artisanpack-tab name="chart" label="Chart" active>
        <div class="p-4">
            <h3 class="text-lg font-bold mb-4">Sales Chart</h3>
            <div class="h-64 bg-base-200 flex items-center justify-center">
                <p>Chart visualization would go here</p>
            </div>
        </div>
    </x-artisanpack-tab>
    
    <x-artisanpack-tab name="table" label="Table">
        <div class="p-4">
            <h3 class="text-lg font-bold mb-4">Sales Data</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>January</td>
                        <td>$10,000</td>
                    </tr>
                    <tr>
                        <td>February</td>
                        <td>$12,500</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-artisanpack-tab>
</x-artisanpack-tabs>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | required | Unique identifier for the tab |
| `label` | string | `null` | Text label for the tab (can also be provided via the `label` slot) |
| `active` | boolean | `false` | Whether this tab is initially active |
| `disabled` | boolean | `false` | Whether this tab is disabled |
| `id` | string | `null` | Optional ID for the tab element |

## Slots

| Slot | Description |
|------|-------------|
| `label` | Content for the tab label (alternative to the `label` prop) |
| `default` | Content to display when the tab is active |

## Behavior

The Tab component:

1. Represents a single tab within a tabbed interface
2. Contains both the tab label (shown in the tab list) and the tab content (shown when active)
3. Can be active, inactive, or disabled
4. Is designed to be used within the Tabs component
5. Shows its content only when it's the active tab

## Styling

The Tab component uses DaisyUI's tab component under the hood, which provides a clean and consistent appearance. You can customize the appearance by:

1. Using the provided props (`active`, `disabled`)
2. Adding custom classes via the `class` attribute
3. Customizing the label content with icons, badges, or other elements

### Default Classes

- Active tabs are highlighted with a border or background color
- Disabled tabs have reduced opacity and cannot be clicked
- Tab content is only visible for the active tab

## Accessibility

The Tab component follows accessibility best practices:

- Uses appropriate ARIA attributes (`role="tab"`, `aria-selected`, etc.)
- Maintains proper focus management
- Supports keyboard navigation

For better accessibility:

1. Provide clear, descriptive labels for each tab
2. Ensure sufficient color contrast for tab labels in all states
3. Make sure tab content is properly structured with headings and semantic HTML

## Related Components

- [Tabs](Tabs) - Container component for multiple tabs
- [Accordion](Accordion) - Alternative way to organize content in collapsible sections
- [Card](Card) - Container for content with similar styling