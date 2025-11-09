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
| [Button](button) | Interactive button element | [View Docs](button) |
| [Checkbox](checkbox) | Checkbox input | [View Docs](checkbox) |
| [CheckboxGroup](checkbox-group) | Group of checkbox inputs | [View Docs](checkbox-group) |
| [Choices](choices) | Multi-select dropdown with search | [View Docs](choices) |
| [ChoicesOffline](choices-offline) | Offline version of Choices | [View Docs](choices-offline) |
| [Colorpicker](colorpicker) | Color selection input | [View Docs](colorpicker) |
| [DatePicker](date-picker) | Date selection input | [View Docs](date-picker) |
| [DateTime](date-time) | Date and time selection input | [View Docs](date-time) |
| [Editor](editor) | Rich text editor | [View Docs](editor) |
| [Fieldset](fieldset) | Styled container for form fields | [View Docs](fieldset) |
| [File](file) | File upload input | [View Docs](file) |
| [Form](form) | Form container with validation | [View Docs](form) |
| [Group](group) | Group of form elements | [View Docs](group) |
| [Input](input) | Text input field | [View Docs](input) |
| [Password](password) | Password input with toggle | [View Docs](password) |
| [Pin](pin) | PIN code input | [View Docs](pin) |
| [Radio](radio) | Radio button input | [View Docs](radio) |
| [RadioGroup](radio-group) | Group of radio button inputs | [View Docs](radio-group) |
| [Range](range) | Range slider input | [View Docs](range) |
| [Rating](rating) | Star rating input | [View Docs](rating) |
| [Select](select) | Dropdown select input | [View Docs](select) |
| [SelectGroup](select-group) | Grouped select input | [View Docs](select-group) |
| [Signature](signature) | Signature pad input | [View Docs](signature) |
| [Tags](tags) | Tags input | [View Docs](tags) |
| [Textarea](textarea) | Multi-line text input | [View Docs](textarea) |
| [Toggle](toggle) | Toggle switch input | [View Docs](toggle) |

### Layout Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Accordion](accordion) | Collapsible content panels | [View Docs](accordion) |
| [Card](card) | Content container with header and footer | [View Docs](card) |
| [Collapse](collapse) | Collapsible content | [View Docs](collapse) |
| [Drawer](drawer) | Side drawer/panel | [View Docs](drawer) |
| [Dropdown](dropdown) | Dropdown menu | [View Docs](dropdown) |
| [Separator](separator) | Horizontal rule with styling | [View Docs](separator) |
| [Main](main) | Main content container | [View Docs](main) |
| [Modal](modal) | Modal dialog | [View Docs](modal) |
| [Popover](popover) | Popover tooltip | [View Docs](popover) |
| [Tabs](tabs) | Tabbed interface | [View Docs](tabs) |
| [Tab](tab) | Individual tab for Tabs component | [View Docs](tab) |

### Navigation Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Breadcrumbs](breadcrumbs) | Breadcrumb navigation | [View Docs](breadcrumbs) |
| [Menu](menu) | Navigation menu | [View Docs](menu) |
| [MenuItem](menu-item) | Menu item for Menu component | [View Docs](menu-item) |
| [MenuSeparator](menu-separator) | Separator for Menu component | [View Docs](menu-separator) |
| [MenuSub](menu-sub) | Submenu for Menu component | [View Docs](menu-sub) |
| [MenuTitle](menu-title) | Title for Menu component | [View Docs](menu-title) |
| [Nav](nav) | Navigation bar | [View Docs](nav) |
| [Pagination](pagination) | Pagination controls | [View Docs](pagination) |
| [Spotlight](spotlight) | Command palette/search | [View Docs](spotlight) |

### Data Display Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Avatar](avatar) | User avatar/profile image | [View Docs](avatar) |
| [Badge](badge) | Small status indicator | [View Docs](badge) |
| [Calendar](calendar) | Calendar display | [View Docs](calendar) |
| [CalendarEnhanced](calendar-enhanced) | Enhanced calendar with events and views | [View Docs](calendar-enhanced) |
| [Chart](chart) | Data visualization charts | [View Docs](chart) |
| [Code](code) | Code display with syntax highlighting | [View Docs](code) |
| [Diff](diff) | Text difference display | [View Docs](diff) |
| [EventModalContent](event-modal-content) | Calendar event modal content | [View Docs](event-modal-content) |
| [Heading](heading) | Styled heading text | [View Docs](heading) |
| [ImageGallery](image-gallery) | Image gallery display | [View Docs](image-gallery) |
| [ImageLibrary](image-library) | Image library/picker | [View Docs](image-library) |
| [ImageSlider](image-slider) | Image slider/carousel component | [View Docs](image-slider) |
| [Kbd](kbd) | Keyboard key display | [View Docs](kbd) |
| [Link](link) | Styled link element | [View Docs](link) |
| [ListItem](list-item) | List item with various layouts | [View Docs](list-item) |
| [Markdown](markdown) | Markdown content display | [View Docs](markdown) |
| [Profile](profile) | User profile display | [View Docs](profile) |
| [Progress](progress) | Progress bar | [View Docs](progress) |
| [ProgressRadial](progress-radial) | Radial progress indicator | [View Docs](progress-radial) |
| [Stat](stat) | Statistics display | [View Docs](stat) |
| [Steps](steps) | Step indicator | [View Docs](steps) |
| [Step](step) | Individual step for Steps component | [View Docs](step) |
| [Subheading](subheading) | Styled subheading text | [View Docs](subheading) |
| [Table](table) | Data table | [View Docs](table) |
| [Text](text) | Styled text component | [View Docs](text) |
| [TimelineItem](timeline-item) | Timeline item display | [View Docs](timeline-item) |

### Feedback Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Alert](alert) | Alert/notification message | [View Docs](alert) |
| [Errors](errors) | Form validation errors display | [View Docs](errors) |
| [Loading](loading) | Loading indicator | [View Docs](loading) |
| [Toast](toast) | Toast notification | [View Docs](toast) |

### Utility Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Carousel](carousel) | Image/content carousel | [View Docs](carousel) |
| [Header](header) | Page header with actions | [View Docs](header) |
| [Icon](icon) | SVG icon display | [View Docs](icon) |
| [Swap](swap) | Element that swaps content on interaction | [View Docs](swap) |
| [ThemeToggle](theme-toggle) | Light/dark theme toggle | [View Docs](theme-toggle) |

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
