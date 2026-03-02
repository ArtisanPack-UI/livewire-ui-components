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
5. **Dashboard Components** - Components for building dashboards (v2.0+)
6. **Feedback Components** - Components for providing feedback to users
7. **Utility Components** - Miscellaneous utility components

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
| [Button](button) | Interactive button element | [Button docs](button) |
| [Checkbox](checkbox) | Checkbox input | [Checkbox docs](checkbox) |
| [CheckboxGroup](checkbox-group) | Group of checkbox inputs | [CheckboxGroup docs](checkbox-group) |
| [Choices](choices) | Multi-select dropdown with search | [Choices docs](choices) |
| [ChoicesOffline](choices-offline) | Offline version of Choices | [ChoicesOffline docs](choices-offline) |
| [Colorpicker](colorpicker) | Color selection input | [Colorpicker docs](colorpicker) |
| [DatePicker](date-picker) | Date selection input | [DatePicker docs](date-picker) |
| [DateTime](date-time) | Date and time selection input | [DateTime docs](date-time) |
| [Editor](editor) | Rich text editor | [Editor docs](editor) |
| [Fieldset](fieldset) | Styled container for form fields | [Fieldset docs](fieldset) |
| [File](file) | File upload input | [File docs](file) |
| [Form](form) | Form container with validation | [Form docs](form) |
| [Group](group) | Group of form elements | [Group docs](group) |
| [Input](input) | Text input field | [Input docs](input) |
| [Password](password) | Password input with toggle | [Password docs](password) |
| [Pin](pin) | PIN code input | [Pin docs](pin) |
| [Radio](radio) | Radio button input | [Radio docs](radio) |
| [RadioGroup](radio-group) | Group of radio button inputs | [RadioGroup docs](radio-group) |
| [Range](range) | Range slider input | [Range docs](range) |
| [Rating](rating) | Star rating input | [Rating docs](rating) |
| [Select](select) | Dropdown select input | [Select docs](select) |
| [SelectGroup](select-group) | Grouped select input | [SelectGroup docs](select-group) |
| [Signature](signature) | Signature pad input | [Signature docs](signature) |
| [Tags](tags) | Tags input | [Tags docs](tags) |
| [Textarea](textarea) | Multi-line text input | [Textarea docs](textarea) |
| [Toggle](toggle) | Toggle switch input | [Toggle docs](toggle) |

### Layout Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Accordion](accordion) | Collapsible content panels | [Accordion docs](accordion) |
| [Card](card) | Content container with header and footer | [Card docs](card) |
| [Collapse](collapse) | Collapsible content | [Collapse docs](collapse) |
| [Drawer](drawer) | Side drawer/panel | [Drawer docs](drawer) |
| [Dropdown](dropdown) | Dropdown menu | [Dropdown docs](dropdown) |
| [Separator](separator) | Horizontal rule with styling | [Separator docs](separator) |
| [Main](main) | Main content container | [Main docs](main) |
| [Modal](modal) | Modal dialog | [Modal docs](modal) |
| [Popover](popover) | Popover tooltip | [Popover docs](popover) |
| [Tabs](tabs) | Tabbed interface | [Tabs docs](tabs) |
| [Tab](tab) | Individual tab for Tabs component | [Tab docs](tab) |

### Navigation Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Breadcrumbs](breadcrumbs) | Breadcrumb navigation | [Breadcrumbs docs](breadcrumbs) |
| [Menu](menu) | Navigation menu | [Menu docs](menu) |
| [MenuItem](menu-item) | Menu item for Menu component | [MenuItem docs](menu-item) |
| [MenuSeparator](menu-separator) | Separator for Menu component | [MenuSeparator docs](menu-separator) |
| [MenuSub](menu-sub) | Submenu for Menu component | [MenuSub docs](menu-sub) |
| [MenuTitle](menu-title) | Title for Menu component | [MenuTitle docs](menu-title) |
| [Nav](nav) | Navigation bar | [Nav docs](nav) |
| [Pagination](pagination) | Pagination controls | [Pagination docs](pagination) |
| [Spotlight](spotlight) | Command palette/search | [Spotlight docs](spotlight) |

### Data Display Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Avatar](avatar) | User avatar/profile image | [Avatar docs](avatar) |
| [Badge](badge) | Small status indicator | [Badge docs](badge) |
| [Calendar](calendar) | Calendar display | [Calendar docs](calendar) |
| [CalendarEnhanced](calendar-enhanced) | Enhanced calendar with events and views | [CalendarEnhanced docs](calendar-enhanced) |
| [Chart](chart) | Data visualization charts | [Chart docs](chart) |
| [Code](code) | Code display with syntax highlighting | [Code docs](code) |
| [Diff](diff) | Text difference display | [Diff docs](diff) |
| [EventModalContent](event-modal-content) | Calendar event modal content | [EventModalContent docs](event-modal-content) |
| [Heading](heading) | Styled heading text | [Heading docs](heading) |
| [ImageGallery](image-gallery) | Image gallery display | [ImageGallery docs](image-gallery) |
| [ImageLibrary](image-library) | Image library/picker | [ImageLibrary docs](image-library) |
| [ImageSlider](image-slider) | Image slider/carousel component | [ImageSlider docs](image-slider) |
| [Kbd](kbd) | Keyboard key display | [Kbd docs](kbd) |
| [Link](link) | Styled link element | [Link docs](link) |
| [ListItem](list-item) | List item with various layouts | [ListItem docs](list-item) |
| [Markdown](markdown) | Markdown content display | [Markdown docs](markdown) |
| [Profile](profile) | User profile display | [Profile docs](profile) |
| [Progress](progress) | Progress bar | [Progress docs](progress) |
| [ProgressRadial](progress-radial) | Radial progress indicator | [ProgressRadial docs](progress-radial) |
| [Stat](stat) | Statistics display | [Stat docs](stat) |
| [Steps](steps) | Step indicator | [Steps docs](steps) |
| [Step](step) | Individual step for Steps component | [Step docs](step) |
| [Subheading](subheading) | Styled subheading text | [Subheading docs](subheading) |
| [Sparkline](sparkline) | Inline sparkline charts | [Sparkline docs](sparkline) |
| [Table](table) | Data table with sorting, export, and more | [Table docs](table) |
| [Text](text) | Styled text component | [Text docs](text) |
| [TimelineItem](timeline-item) | Timeline item display | [TimelineItem docs](timeline-item) |

### Dashboard Components (v2.0+)

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [KpiCard](kpi-card) | KPI card with sparkline and trend indicator | [KpiCard docs](kpi-card) |
| [WidgetGrid](widget-grid) | Responsive grid for dashboard layouts | [WidgetGrid docs](widget-grid) |
| [StreamableContent](streamable-content) | Container for streaming content (AI responses) | [StreamableContent docs](streamable-content) |

### Feedback Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Alert](alert) | Alert/notification message | [Alert docs](alert) |
| [Errors](errors) | Form validation errors display | [Errors docs](errors) |
| [Loading](loading) | Loading indicator | [Loading docs](loading) |
| [Toast](toast) | Toast notification | [Toast docs](toast) |

### Utility Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Carousel](carousel) | Image/content carousel | [Carousel docs](carousel) |
| [Header](header) | Page header with actions | [Header docs](header) |
| [Icon](icon) | SVG icon display | [Icon docs](icon) |
| [Swap](swap) | Element that swaps content on interaction | [Swap docs](swap) |
| [ThemeToggle](theme-toggle) | Light/dark theme toggle | [ThemeToggle docs](theme-toggle) |

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

### Data Table with Export (v2.0+)

```php
<?php
use ArtisanPack\LivewireUiComponents\Traits\WithTableExport;
use Livewire\Volt\Component;

new class extends Component {
    use WithTableExport;

    public function with(): array
    {
        return [
            'headers' => [
                ['key' => 'name', 'label' => 'Name'],
                ['key' => 'email', 'label' => 'Email'],
                ['key' => 'role', 'label' => 'Role'],
            ],
            'users' => User::paginate(10),
        ];
    }

    public function getTableExportData(string $tableId = 'default'): array
    {
        return [
            'headers' => $this->with()['headers'],
            'rows' => User::all()->toArray(),
            'filename' => 'users-export',
        ];
    }
}; ?>

<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    exportable
    :export-formats="['csv', 'xlsx', 'pdf']"
    with-pagination />
```

### Dashboard with KPI Cards (v2.0+)

```php
<x-artisanpack-widget-grid :cols="4" :gap="4">
    <x-artisanpack-kpi-card
        title="Total Revenue"
        value="$45,231"
        icon="o-currency-dollar"
        :change="12.5"
        change-label="vs last month"
        :sparkline-data="[1200, 1350, 1100, 1500, 1400, 1600, 1800]" />

    <x-artisanpack-kpi-card
        title="Active Users"
        value="2,345"
        icon="o-users"
        :change="8.2"
        change-label="vs last month" />

    <x-artisanpack-kpi-card
        title="Orders"
        value="1,234"
        icon="o-shopping-cart"
        :change="-2.4"
        change-label="vs last month" />

    <x-artisanpack-kpi-card
        title="Conversion"
        value="3.24%"
        icon="o-arrow-path"
        :change="0.8"
        change-label="improvement" />
</x-artisanpack-widget-grid>
```

## Next Steps

Explore the documentation for individual components to learn more about their specific features, props, slots, and usage examples.
