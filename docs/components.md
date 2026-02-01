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
| [Button](Button) | Interactive button element | [View Docs](Button) |
| [Checkbox](Checkbox) | Checkbox input | [View Docs](Checkbox) |
| [CheckboxGroup](Checkbox-Group) | Group of checkbox inputs | [View Docs](Checkbox-Group) |
| [Choices](Choices) | Multi-select dropdown with search | [View Docs](Choices) |
| [ChoicesOffline](Choices-Offline) | Offline version of Choices | [View Docs](Choices-Offline) |
| [Colorpicker](Colorpicker) | Color selection input | [View Docs](Colorpicker) |
| [DatePicker](Date-Picker) | Date selection input | [View Docs](Date-Picker) |
| [DateTime](Date-Time) | Date and time selection input | [View Docs](Date-Time) |
| [Editor](Editor) | Rich text editor | [View Docs](Editor) |
| [Fieldset](Fieldset) | Styled container for form fields | [View Docs](Fieldset) |
| [File](File) | File upload input | [View Docs](File) |
| [Form](Form) | Form container with validation | [View Docs](Form) |
| [Group](Group) | Group of form elements | [View Docs](Group) |
| [Input](Input) | Text input field | [View Docs](Input) |
| [Password](Password) | Password input with toggle | [View Docs](Password) |
| [Pin](Pin) | PIN code input | [View Docs](Pin) |
| [Radio](Radio) | Radio button input | [View Docs](Radio) |
| [RadioGroup](Radio-Group) | Group of radio button inputs | [View Docs](Radio-Group) |
| [Range](Range) | Range slider input | [View Docs](Range) |
| [Rating](Rating) | Star rating input | [View Docs](Rating) |
| [Select](Select) | Dropdown select input | [View Docs](Select) |
| [SelectGroup](Select-Group) | Grouped select input | [View Docs](Select-Group) |
| [Signature](Signature) | Signature pad input | [View Docs](Signature) |
| [Tags](Tags) | Tags input | [View Docs](Tags) |
| [Textarea](Textarea) | Multi-line text input | [View Docs](Textarea) |
| [Toggle](Toggle) | Toggle switch input | [View Docs](Toggle) |

### Layout Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Accordion](Accordion) | Collapsible content panels | [View Docs](Accordion) |
| [Card](Card) | Content container with header and footer | [View Docs](Card) |
| [Collapse](Collapse) | Collapsible content | [View Docs](Collapse) |
| [Drawer](Drawer) | Side drawer/panel | [View Docs](Drawer) |
| [Dropdown](Dropdown) | Dropdown menu | [View Docs](Dropdown) |
| [Separator](Separator) | Horizontal rule with styling | [View Docs](Separator) |
| [Main](Main) | Main content container | [View Docs](Main) |
| [Modal](Modal) | Modal dialog | [View Docs](Modal) |
| [Popover](Popover) | Popover tooltip | [View Docs](Popover) |
| [Tabs](Tabs) | Tabbed interface | [View Docs](Tabs) |
| [Tab](Tab) | Individual tab for Tabs component | [View Docs](Tab) |

### Navigation Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Breadcrumbs](Breadcrumbs) | Breadcrumb navigation | [View Docs](Breadcrumbs) |
| [Menu](Menu) | Navigation menu | [View Docs](Menu) |
| [MenuItem](Menu-Item) | Menu item for Menu component | [View Docs](Menu-Item) |
| [MenuSeparator](Menu-Separator) | Separator for Menu component | [View Docs](Menu-Separator) |
| [MenuSub](Menu-Sub) | Submenu for Menu component | [View Docs](Menu-Sub) |
| [MenuTitle](Menu-Title) | Title for Menu component | [View Docs](Menu-Title) |
| [Nav](Nav) | Navigation bar | [View Docs](Nav) |
| [Pagination](Pagination) | Pagination controls | [View Docs](Pagination) |
| [Spotlight](Spotlight) | Command palette/search | [View Docs](Spotlight) |

### Data Display Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Avatar](Avatar) | User avatar/profile image | [View Docs](Avatar) |
| [Badge](Badge) | Small status indicator | [View Docs](Badge) |
| [Calendar](Calendar) | Calendar display | [View Docs](Calendar) |
| [CalendarEnhanced](Calendar-Enhanced) | Enhanced calendar with events and views | [View Docs](Calendar-Enhanced) |
| [Chart](Chart) | Data visualization charts | [View Docs](Chart) |
| [Code](Code) | Code display with syntax highlighting | [View Docs](Code) |
| [Diff](Diff) | Text difference display | [View Docs](Diff) |
| [EventModalContent](Event-Modal-Content) | Calendar event modal content | [View Docs](Event-Modal-Content) |
| [Heading](Heading) | Styled heading text | [View Docs](Heading) |
| [ImageGallery](Image-Gallery) | Image gallery display | [View Docs](Image-Gallery) |
| [ImageLibrary](Image-Library) | Image library/picker | [View Docs](Image-Library) |
| [ImageSlider](Image-Slider) | Image slider/carousel component | [View Docs](Image-Slider) |
| [Kbd](Kbd) | Keyboard key display | [View Docs](Kbd) |
| [Link](Link) | Styled link element | [View Docs](Link) |
| [ListItem](List-Item) | List item with various layouts | [View Docs](List-Item) |
| [Markdown](Markdown) | Markdown content display | [View Docs](Markdown) |
| [Profile](Profile) | User profile display | [View Docs](Profile) |
| [Progress](Progress) | Progress bar | [View Docs](Progress) |
| [ProgressRadial](Progress-Radial) | Radial progress indicator | [View Docs](Progress-Radial) |
| [Stat](Stat) | Statistics display | [View Docs](Stat) |
| [Steps](Steps) | Step indicator | [View Docs](Steps) |
| [Step](Step) | Individual step for Steps component | [View Docs](Step) |
| [Subheading](Subheading) | Styled subheading text | [View Docs](Subheading) |
| [Sparkline](Sparkline) | Inline sparkline charts | [View Docs](Sparkline) |
| [Table](Table) | Data table with sorting, export, and more | [View Docs](Table) |
| [Text](Text) | Styled text component | [View Docs](Text) |
| [TimelineItem](Timeline-Item) | Timeline item display | [View Docs](Timeline-Item) |

### Dashboard Components (v2.0+)

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [KpiCard](Kpi-Card) | KPI card with sparkline and trend indicator | [View Docs](Kpi-Card) |
| [WidgetGrid](Widget-Grid) | Responsive grid for dashboard layouts | [View Docs](Widget-Grid) |
| [StreamableContent](Streamable-Content) | Container for streaming content (AI responses) | [View Docs](Streamable-Content) |

### Feedback Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Alert](Alert) | Alert/notification message | [View Docs](Alert) |
| [Errors](Errors) | Form validation errors display | [View Docs](Errors) |
| [Loading](Loading) | Loading indicator | [View Docs](Loading) |
| [Toast](Toast) | Toast notification | [View Docs](Toast) |

### Utility Components

| Component | Description | Documentation |
|-----------|-------------|---------------|
| [Carousel](Carousel) | Image/content carousel | [View Docs](Carousel) |
| [Header](Header) | Page header with actions | [View Docs](Header) |
| [Icon](Icon) | SVG icon display | [View Docs](Icon) |
| [Swap](Swap) | Element that swaps content on interaction | [View Docs](Swap) |
| [ThemeToggle](Theme-Toggle) | Light/dark theme toggle | [View Docs](Theme-Toggle) |

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
