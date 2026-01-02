## ArtisanPack UI Livewire UI Components

This package provides a comprehensive set of 70+ pre-built UI components for Livewire, powered by daisyUI and Tailwind CSS. It's built specifically for the TALL stack (Tailwind CSS, Alpine.js, Laravel, and Livewire) and accelerates Laravel application development with beautiful, responsive, and accessible components.

### Installation

After running `composer require artisanpack-ui/livewire-ui-components`, install the package with:

@verbatim
<code-snippet name="Install Livewire UI Components" lang="bash">
php artisan livewire-ui-components:install
npm run dev
</code-snippet>
@endverbatim

### Component Naming Convention

All components use the `<x-artisanpack-{component-name}>` naming pattern. The old `<x-artisanpack-{name}>` syntax is deprecated and should not be used in new code.

@verbatim
<code-snippet name="Component naming syntax" lang="blade">
<!-- Correct (use this) -->
<x-artisanpack-button>Click Me</x-artisanpack-button>
<x-artisanpack-input wire:model="name" />

<!-- Deprecated (do not use) -->
<x-artisanpack-button>Click Me</x-artisanpack-artisanpack-button>
</code-snippet>
@endverbatim

### Component Categories

**Form Components**: input, button, checkbox, select, datepicker, file, textarea, radio, toggle, range, color-picker, rich-text-editor

**Layout Components**: card, modal, tabs, accordion, drawer, dropdown, collapse, divider, stack, grid

**Navigation Components**: menu, breadcrumbs, pagination, steps, navbar, sidebar, spotlight-search

**Data Display Components**: table, chart, calendar, avatar, badge, progress, stat, timeline, carousel, diff

**Feedback Components**: alert, toast, loading, skeleton, empty-state, error

**Utility Components**: icon, theme-toggle, tooltip, clipboard

### Common Usage Patterns

@verbatim
<code-snippet name="Button component" lang="blade">
<!-- Basic button -->
<x-artisanpack-button>Click Me</x-artisanpack-button>

<!-- Button with color and icon -->
<x-artisanpack-button color="primary" icon="o-plus">
    Add Item
</x-artisanpack-button>

<!-- Livewire action button -->
<x-artisanpack-button wire:click="save" color="success">
    Save
</x-artisanpack-button>
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Card component" lang="blade">
<x-artisanpack-card>
    <x-slot:header>
        <h3 class="text-lg font-bold">Card Title</h3>
    </x-slot:header>

    <p>Card content goes here.</p>

    <x-slot:footer>
        <x-artisanpack-button color="primary">Action</x-artisanpack-button>
    </x-slot:footer>
</x-artisanpack-card>
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Form input component" lang="blade">
<!-- Basic input -->
<x-artisanpack-input
    label="Name"
    wire:model="name"
    placeholder="Enter your name"
/>

<!-- Input with validation error -->
<x-artisanpack-input
    label="Email"
    wire:model="email"
    type="email"
    error="{{ $errors->first('email') }}"
/>

<!-- Input with icon and hint -->
<x-artisanpack-input
    label="Search"
    wire:model.live="search"
    icon="o-magnifying-glass"
    hint="Type to search..."
/>
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Select component" lang="blade">
<x-artisanpack-select
    label="Country"
    wire:model="country"
    :options="$countries"
    option-value="id"
    option-label="name"
/>
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Modal component" lang="blade">
<x-artisanpack-modal wire:model="showModal" title="Confirm Action">
    <p>Are you sure you want to proceed?</p>

    <x-slot:actions>
        <x-artisanpack-button wire:click="$set('showModal', false)">
            Cancel
        </x-artisanpack-button>
        <x-artisanpack-button wire:click="confirm" color="primary">
            Confirm
        </x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-modal>
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Table component" lang="blade">
<x-artisanpack-table :headers="$headers" :rows="$users">
    @scope('cell_name', $user)
        <strong>{{ $user->name }}</strong>
    @endscope

    @scope('cell_actions', $user)
        <x-artisanpack-button
            wire:click="edit({{ $user->id }})"
            size="sm"
            icon="o-pencil"
        />
    @endscope
</x-artisanpack-table>
</code-snippet>
@endverbatim

### Theming

Generate custom color themes to match your brand:

@verbatim
<code-snippet name="Generate custom theme" lang="bash">
php artisan artisanpack:generate-theme
</code-snippet>
@endverbatim

This interactive command creates custom color schemes that work across all components.

### Icons

The package integrates with Heroicons and Font Awesome. Use icon names with the appropriate prefix:
- Heroicons outline: `o-{icon-name}` (e.g., `o-user`, `o-home`)
- Heroicons solid: `s-{icon-name}` (e.g., `s-user`, `s-home`)
- Font Awesome: `fa-{icon-name}` (e.g., `fa-user`, `fa-home`)

### Best Practices

1. **Always use the `<x-artisanpack-{name}>` prefix** for component names (not the deprecated `artisanpack-artisanpack` double prefix)
2. **Use wire:model for form inputs** to enable Livewire's reactive data binding
3. **Leverage slots** (header, footer, actions) for complex component layouts
4. **Apply daisyUI color variants** via the `color` attribute: primary, secondary, accent, success, warning, error, info
5. **Utilize built-in validation** by passing error messages to the `error` attribute on form components
6. **Use wire:loading** with loading components to provide user feedback during Livewire requests

### Integration with ArtisanPack UI Ecosystem

This package is part of the ArtisanPack UI ecosystem and integrates seamlessly with:
- `artisanpack-ui/accessibility` - Accessibility utilities and ARIA helpers
- `artisanpack-ui/security` - Security features and CSRF protection
- `artisanpack-ui/core` - Core utilities and helpers
- `artisanpack-ui/icons` - Extended icon support
- `artisanpack-ui/hooks` - WordPress-style hooks and filters for Laravel

### Resources

- Documentation: https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/wikis/home
- Component Reference: https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/wikis/components
- Repository: https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
