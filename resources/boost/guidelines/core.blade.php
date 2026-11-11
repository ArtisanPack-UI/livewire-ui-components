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
<x-artisanpack-input wire:model="name" :label="__( 'Name' )" />

<!-- Deprecated (do not use) -->
<x-artisanpack-button>Click Me</x-artisanpack-artisanpack-button>
</code-snippet>
@endverbatim

### Component Categories

**Form Components**: input, button, checkbox, select, date-picker, file, textarea, password, pin, radio-group, checkbox-group, rating, toggle, range, fieldset, form, validation-summary, color-picker, rich-text-editor, code-editor, signature, combobox (errors is deprecated: use validation-summary; choices, choices-offline and tags are deprecated: use combobox, with multiple and allow-create for tags; radio and group are deprecated: use radio-group, group as variant="segmented"; colorpicker is a deprecated alias of color-picker; select-group is deprecated: use select with option groups; datepicker is a deprecated alias of date-picker; datetime is deprecated: use date-picker with time; editor is a deprecated alias of rich-text-editor)

**Layout Components**: card, dialog, tabs, accordion, drawer, dropdown-menu, collapse, divider, stack, grid (modal and dropdown are deprecated)

**Navigation Components**: menu, breadcrumbs, pagination, steps, navbar, sidebar, spotlight (nav is deprecated; tabs and menu take `tab` and `menu-item` children, or `tabs` and `items` as data; steps takes `step` children or `steps` as data, with `wire:model` binding the current step; pagination takes a Laravel paginator in `rows` or `page`, `total` and `per-page`; link renders a button when it has no `href`)

**Data Display Components**: table, chart, calendar, avatar, badge, progress, stat, timeline, carousel, diff

**Feedback Components**: alert, toast and toaster, loading, skeleton, empty-state, error

**Utility Components**: icon, theme-toggle, tooltip, clipboard

### Common Usage Patterns

@verbatim
<code-snippet name="Button component" lang="blade">
<!-- Basic button -->
<x-artisanpack-button>Click Me</x-artisanpack-button>

<!-- Variant, color, size and a leading icon (Lucide names; o-*/fa-* names still resolve through blade-icons) -->
<x-artisanpack-button variant="soft" color="primary" size="sm" leading-icon="plus">
    Add Item
</x-artisanpack-button>

<!-- Livewire action button with a loading state -->
<x-artisanpack-button wire:click="save" color="success" :loading="$saving" type="submit">
    Save
</x-artisanpack-button>

<!-- Link styled as a button; icon-only buttons need an aria-label -->
<x-artisanpack-button href="/reports" variant="outline" color="neutral">Reports</x-artisanpack-button>
<x-artisanpack-button icon-only leading-icon="trash" variant="ghost" color="error" :aria-label="__( 'Delete' )" />
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Card component" lang="blade">
<x-artisanpack-card :title="__( 'Card Title' )" :description="__( 'Updated 2 hours ago' )" surface="raised">
    <x-slot:actions>
        <x-artisanpack-button variant="ghost" color="neutral" size="sm">{{ __( 'Share' ) }}</x-artisanpack-button>
    </x-slot:actions>

    <p>Card content goes here.</p>

    <x-slot:footer>
        <x-artisanpack-button>{{ __( 'Action' ) }}</x-artisanpack-button>
    </x-slot:footer>
</x-artisanpack-card>

<!-- href links the title (it needs a title) and makes the whole card clickable -->
<x-artisanpack-card href="/reports/q3" :title="__( 'Q3 report' )" media="/images/q3.png" />
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

<!-- The error comes from $errors for the wire:model name (or error-field) -->
<x-artisanpack-input
    label="Email"
    wire:model="email"
    type="email"
    required
/>

<!-- Leading/trailing icons, prefix/suffix and a hint -->
<x-artisanpack-input
    label="Search"
    wire:model.live="search"
    leading-icon="search"
    hint="Type to search..."
/>
<x-artisanpack-input label="Price" wire:model="price" type="number" prefix="USD" suffix="/mo" />
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Select component" lang="blade">
<!-- Options are strings or [ 'value' => …, 'label' => …, 'disabled' => … ] entries -->
<x-artisanpack-select
    label="Country"
    wire:model="country"
    :options="[
        [ 'value' => 'us', 'label' => __( 'United States' ) ],
        [ 'value' => 'ca', 'label' => __( 'Canada' ) ],
    ]"
/>

<!-- Option children render after the options; placeholder="" drops the empty first option -->
<x-artisanpack-select label="Plan" wire:model="plan" placeholder="">
    <option value="starter">{{ __( 'Starter' ) }}</option>
    <option value="team">{{ __( 'Team' ) }}</option>
</x-artisanpack-select>
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Checkbox component" lang="blade">
<x-artisanpack-checkbox wire:model="terms" :label="__( 'I agree to the terms' )" required />
<x-artisanpack-checkbox
    :label="__( 'Select all' )"
    :checked="$allSelected"
    :indeterminate="$someSelected"
    :description="__( 'Applies to every row on this page.' )"
/>
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Dialog component" lang="blade">
<!-- wire:model binds the open state; Escape, the close button and the backdrop close it -->
<x-artisanpack-dialog wire:model="showInvite" :trigger="__( 'Invite member' )" :title="__( 'Invite member' )" :description="__( 'They\'ll get an email with a link to join the project.' )">
    <x-artisanpack-input type="email" :label="__( 'Email' )" wire:model="email" />

    <x-slot:footer>
        <x-artisanpack-button variant="outline" color="neutral" wire:click="$set( 'showInvite', false )">{{ __( 'Cancel' ) }}</x-artisanpack-button>
        <x-artisanpack-button wire:click="invite">{{ __( 'Send invite' ) }}</x-artisanpack-button>
    </x-slot:footer>
</x-artisanpack-dialog>

<!-- Destructive confirmations are alertdialogs; name the action, not "OK" -->
<x-artisanpack-dialog wire:model="confirmingDelete" role="alertdialog" size="sm" color="error" icon="trash" :title="__( 'Delete listing?' )">
    <x-slot:footer>
        <x-artisanpack-button variant="outline" color="neutral" wire:click="$set( 'confirmingDelete', false )">{{ __( 'Cancel' ) }}</x-artisanpack-button>
        <x-artisanpack-button color="error" wire:click="delete">{{ __( 'Delete listing' ) }}</x-artisanpack-button>
    </x-slot:footer>
</x-artisanpack-dialog>
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="DropdownMenu component" lang="blade">
<!-- Choosing an item clicks it, so wire:click runs from the keyboard too. Pass an id when the same menu repeats (table rows). -->
<x-artisanpack-dropdown-menu :id="'row-'.$listing->id" :label="__( 'Options' )" align="end">
    <x-artisanpack-dropdown-menu-item value="edit" icon="pencil" shortcut="E" wire:click="edit">{{ __( 'Edit' ) }}</x-artisanpack-dropdown-menu-item>
    <x-artisanpack-dropdown-menu-item type="checkbox" value="grid" :checked="$showGrid" wire:click="$toggle( 'showGrid' )">{{ __( 'Show grid' ) }}</x-artisanpack-dropdown-menu-item>
    <x-artisanpack-dropdown-menu-separator />
    <x-artisanpack-dropdown-menu-item value="delete" icon="trash" color="error" wire:click="delete">{{ __( 'Delete' ) }}</x-artisanpack-dropdown-menu-item>
</x-artisanpack-dropdown-menu>
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Toasts" lang="blade">
<!-- Once per page, in the layout -->
<x-artisanpack-toaster />

<!-- In a Livewire action (use ArtisanPack\LivewireUiComponents\Traits\Toast): -->
<!-- $this->toast( __( 'Listing saved' ), color: 'success' ); -->
<!-- $this->dispatch( 'toast', title: __( 'Listing saved' ), color: 'success' ); -->
<!-- Across a redirect: $this->toast( __( 'Listing saved' ), redirectTo: route( 'listings' ) ); or Toasts::flash( title: … ) -->
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
            variant="ghost"
            icon-only
            leading-icon="o-pencil"
            :aria-label="__( 'Edit' )"
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
