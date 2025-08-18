---
title: Components Overview
---

# Components Overview

ArtisanPack UI Livewire Components provides a comprehensive set of UI components for Laravel applications. This page provides an overview of all available components, organized by category.

## Component Categories

The components are organized into the following categories:

1. **Form Components** - Components for building forms and collecting user input
2. **Layout Components** - Components for structuring and organizing content
3. **Navigation Components** - Components for navigation and menus
4. **Data Display Components** - Components for displaying and visualizing data
5. **Feedback Components** - Components for providing feedback to users
6. **Utility Components** - Miscellaneous utility components

## Usage Patterns

All ArtisanPack UI components follow consistent usage patterns:

### Basic Component Usage

```php
<x-artisanpack-button>Click Me</x-artisanpack-button>
```

### Components with Properties

```php
<x-artisanpack-button color="primary" size="lg" outline>
    Large Primary Outline Button
</x-artisanpack-button>
```

### Components with Slots

```php
<x-artisanpack-card>
    <x-slot:header>Card Header</x-slot:header>
    Card Content
    <x-slot:footer>Card Footer</x-slot:footer>
</x-artisanpack-card>
```

### Components with Events

```php
<x-artisanpack-button @click="alert('Button clicked!')">
    Click Me
</x-artisanpack-button>
```

## Common Props and Slots

Many components share common props and slots:

### Common Props

- `class` - Additional CSS classes to apply to the component
- `id` - HTML ID attribute
- `disabled` - Whether the component is disabled
- `color` - The color variant (primary, secondary, accent, etc.)
- `size` - The size variant (xs, sm, md, lg, xl)

### Common Slots

- `default` - The main content of the component
- `header` - Content for the header section (for container components)
- `footer` - Content for the footer section (for container components)
- `icon` - Icon content for components that support icons

## Component List

### Form Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Button](button.md) | Interactive button element | [View Docs](button.md) |
| [Checkbox](checkbox.md) | Checkbox input | [View Docs](checkbox.md) |
| [Choices](choices.md) | Multi-select dropdown with search | [View Docs](choices.md) |
| [ChoicesOffline](choices-offline.md) | Offline version of Choices | [View Docs](choices-offline.md) |
| [Colorpicker](colorpicker.md) | Color selection input | [View Docs](colorpicker.md) |
| [DatePicker](datepicker.md) | Date selection input | [View Docs](datepicker.md) |
| [DateTime](datetime.md) | Date and time selection input | [View Docs](datetime.md) |
| [Editor](editor.md) | Rich text editor | [View Docs](editor.md) |
| [File](file.md) | File upload input | [View Docs](file.md) |
| [Form](form.md) | Form container with validation | [View Docs](form.md) |
| [Group](group.md) | Group of form elements | [View Docs](group.md) |
| [Input](input.md) | Text input field | [View Docs](input.md) |
| [Password](password.md) | Password input with toggle | [View Docs](password.md) |
| [Pin](pin.md) | PIN code input | [View Docs](pin.md) |
| [Radio](radio.md) | Radio button input | [View Docs](radio.md) |
| [Range](range.md) | Range slider input | [View Docs](range.md) |
| [Rating](rating.md) | Star rating input | [View Docs](rating.md) |
| [Select](select.md) | Dropdown select input | [View Docs](select.md) |
| [SelectGroup](select-group.md) | Grouped select input | [View Docs](select-group.md) |
| [Signature](signature.md) | Signature pad input | [View Docs](signature.md) |
| [Tags](tags.md) | Tags input | [View Docs](tags.md) |
| [Textarea](textarea.md) | Multi-line text input | [View Docs](textarea.md) |
| [Toggle](toggle.md) | Toggle switch input | [View Docs](toggle.md) |

### Layout Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Accordion](accordion.md) | Collapsible content panels | [View Docs](accordion.md) |
| [Card](card.md) | Content container with header and footer | [View Docs](card.md) |
| [Collapse](collapse.md) | Collapsible content | [View Docs](collapse.md) |
| [Drawer](drawer.md) | Side drawer/panel | [View Docs](drawer.md) |
| [Dropdown](dropdown.md) | Dropdown menu | [View Docs](dropdown.md) |
| [Hr](hr.md) | Horizontal rule with styling | [View Docs](hr.md) |
| [Main](main.md) | Main content container | [View Docs](main.md) |
| [Modal](modal.md) | Modal dialog | [View Docs](modal.md) |
| [Popover](popover.md) | Popover tooltip | [View Docs](popover.md) |
| [Tabs](tabs.md) | Tabbed interface | [View Docs](tabs.md) |
| [Tab](tab.md) | Individual tab for Tabs component | [View Docs](tab.md) |

### Navigation Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Breadcrumbs](breadcrumbs.md) | Breadcrumb navigation | [View Docs](breadcrumbs.md) |
| [Menu](menu.md) | Navigation menu | [View Docs](menu.md) |
| [MenuItem](menu-item.md) | Menu item for Menu component | [View Docs](menu-item.md) |
| [MenuSeparator](menu-separator.md) | Separator for Menu component | [View Docs](menu-separator.md) |
| [MenuSub](menu-sub.md) | Submenu for Menu component | [View Docs](menu-sub.md) |
| [MenuTitle](menu-title.md) | Title for Menu component | [View Docs](menu-title.md) |
| [Nav](nav.md) | Navigation bar | [View Docs](nav.md) |
| [Pagination](pagination.md) | Pagination controls | [View Docs](pagination.md) |
| [Spotlight](spotlight.md) | Command palette/search | [View Docs](spotlight.md) |

### Data Display Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Avatar](avatar.md) | User avatar/profile image | [View Docs](avatar.md) |
| [Badge](badge.md) | Small status indicator | [View Docs](badge.md) |
| [Calendar](calendar.md) | Calendar display | [View Docs](calendar.md) |
| [Chart](chart.md) | Data visualization charts | [View Docs](chart.md) |
| [Code](code.md) | Code display with syntax highlighting | [View Docs](code.md) |
| [Diff](diff.md) | Text difference display | [View Docs](diff.md) |
| [ImageGallery](image-gallery.md) | Image gallery display | [View Docs](image-gallery.md) |
| [ImageLibrary](image-library.md) | Image library/picker | [View Docs](image-library.md) |
| [Kbd](kbd.md) | Keyboard key display | [View Docs](kbd.md) |
| [ListItem](list-item.md) | List item with various layouts | [View Docs](list-item.md) |
| [Markdown](markdown.md) | Markdown content display | [View Docs](markdown.md) |
| [Progress](progress.md) | Progress bar | [View Docs](progress.md) |
| [ProgressRadial](progress-radial.md) | Radial progress indicator | [View Docs](progress-radial.md) |
| [Stat](stat.md) | Statistics display | [View Docs](stat.md) |
| [Steps](steps.md) | Step indicator | [View Docs](steps.md) |
| [Step](step.md) | Individual step for Steps component | [View Docs](step.md) |
| [Table](table.md) | Data table | [View Docs](table.md) |
| [TimelineItem](timeline-item.md) | Timeline item display | [View Docs](timeline-item.md) |

### Feedback Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Alert](alert.md) | Alert/notification message | [View Docs](alert.md) |
| [Errors](errors.md) | Form validation errors display | [View Docs](errors.md) |
| [Loading](loading.md) | Loading indicator | [View Docs](loading.md) |
| [Toast](toast.md) | Toast notification | [View Docs](toast.md) |

### Utility Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Carousel](carousel.md) | Image/content carousel | [View Docs](carousel.md) |
| [Header](header.md) | Page header with actions | [View Docs](header.md) |
| [Icon](icon.md) | SVG icon display | [View Docs](icon.md) |
| [Swap](swap.md) | Element that swaps content on interaction | [View Docs](swap.md) |
| [ThemeToggle](theme-toggle.md) | Light/dark theme toggle | [View Docs](theme-toggle.md) |

## Component Composition

ArtisanPack UI components are designed to work together seamlessly. Here are some examples of component composition:

### Form with Validation

```php
<x-artisanpack-form wire:submit="save">
    <x-artisanpack-input label="Name" wire:model="name" required />
    <x-artisanpack-input label="Email" wire:model="email" type="email" required />
    <x-artisanpack-textarea label="Message" wire:model="message" required />
    
    <x-slot:actions>
        <x-artisanpack-button type="submit">Submit</x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-form>
```

### Card with Tabs

```php
<x-artisanpack-card>
    <x-slot:header>
        <h3 class="text-lg font-bold">User Profile</h3>
    </x-slot:header>
    
    <x-artisanpack-tabs>
        <x-artisanpack-tab name="info" label="Information" active>
            <div class="p-4">User information content</div>
        </x-artisanpack-tab>
        
        <x-artisanpack-tab name="settings" label="Settings">
            <div class="p-4">User settings content</div>
        </x-artisanpack-tab>
    </x-artisanpack-tabs>
</x-artisanpack-card>
```

### Data Table with Pagination

```php
<x-artisanpack-table :headers="['Name', 'Email', 'Role', 'Actions']">
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td>
                <x-artisanpack-button size="sm" wire:click="edit({{ $user->id }})">
                    Edit
                </x-artisanpack-button>
            </td>
        </tr>
    @endforeach
    
    <x-slot:pagination>
        {{ $users->links() }}
    </x-slot:pagination>
</x-artisanpack-table>
```

## Next Steps

Explore the documentation for individual components to learn more about their specific features, props, slots, and usage examples.
