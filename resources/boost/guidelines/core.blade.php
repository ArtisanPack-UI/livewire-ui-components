{{-- One verbatim block: the guide is Markdown, and its tags and directives are examples, not Blade. --}}
@verbatim
## ArtisanPack UI Livewire UI Components

Livewire UI Components 3.0 renders the ArtisanPack UI design system's components as Blade components, with Livewire bindings. The markup, ARIA and behavior match the design system's React and Vue packages; the styles come from the design system's CSS (`.apui-*` classes and `--apui-*` tokens). There is no DaisyUI: classes such as `btn`, `card`, `badge`, `modal` and `bg-base-200` style nothing.

### Installation

<code-snippet name="Install Livewire UI Components" lang="bash">
composer require artisanpack-ui/livewire-ui-components
php artisan livewire-ui-components:install
</code-snippet>

Without a bundler, the installer publishes the prebuilt assets. The layout's `<head>` loads them, with the theme script first, and the toaster goes once in the layout:

<code-snippet name="Layout" lang="blade">
<head>
    @apuiThemeScript
    @artisanpackStyles
    @artisanpackScripts
</head>
<body>
    {{ $slot }}

    <x-artisanpack-toaster />
</body>
</code-snippet>

With Vite, import `@artisanpack-ui/css` in `resources/css/app.css` and register `@artisanpack-ui/behavior/alpine` on `alpine:init` in `resources/js/app.js`, and keep `@apuiThemeScript` before `@vite`.

### Component naming

Tags are `<x-artisanpack-{name}>`, with the name in kebab-case. Props are kebab-case attributes (`leading-icon`), PHP values bind with a colon (`:options="$countries"`), and per-part classes go in `:classes`, keyed by part (`:classes="[ 'label' => 'sr-only' ]"`).

<code-snippet name="Component naming" lang="blade">
<x-artisanpack-button>{{ __( 'Save' ) }}</x-artisanpack-button>
<x-artisanpack-input wire:model="name" :label="__( 'Name' )" />
<x-artisanpack-dropdown-menu :label="__( 'Options' )">…</x-artisanpack-dropdown-menu>
</code-snippet>

The prefix comes from the `prefix` config key (default `artisanpack`). Use 3.0 names; the 2.x names are deprecated aliases: `dialog` not `modal`, `dropdown-menu` not `dropdown`, `combobox` not `choices`, `choices-offline` or `tags`, `page-header` not `header`, `app-shell` not `main`, `navbar` not `nav`, `radio-group` not `radio` or `group`, `validation-summary` not `errors`, `divider` not `separator`, `date-picker` not `datepicker` or `datetime`, `color-picker` not `colorpicker`, `rich-text-editor` not `editor`, `user-menu` not `profile`, `grid` not `widget-grid`. `php artisan artisanpack:upgrade-ui` rewrites 2.x Blade for you.

### Components

**Actions**: button, link, clipboard, swap

**Forms**: form, fieldset, input, password, pin, textarea, select, combobox, checkbox, checkbox-group, radio-group, toggle, range, rating, color-picker, date-picker, file, signature, validation-summary

**Editors and content**: rich-text-editor, code-editor, markdown, code, kbd, diff, heading, text, divider, icon

**Data display**: badge, avatar, avatar-group, stat, kpi-card, sparkline, table, chart, timeline, list and list-item, calendar (`<livewire:artisanpack-calendar>` keeps state; `<x-artisanpack-calendar>` is the stateless markup)

**Feedback**: alert, toast and toaster, loading, progress, progress-radial, skeleton, streamable-content, empty-state, error-state

**Overlays**: dialog, drawer, popover, tooltip, dropdown-menu, spotlight

**Navigation**: navbar, menu, user-menu, tabs, steps, pagination, breadcrumbs

**Layout and disclosure**: app-shell, page-header, card, grid, stack, accordion, collapse, carousel, image-gallery, image-slider, image-library

**Theming**: theme-toggle, theme-preview

Tabs, Menu, Steps, Breadcrumbs, Navbar and DropdownMenu take child components (`tab`, `menu-item`, `step`, …) or the same entries as data (`tabs`, `items`, `steps`, `links`). Overlays bind their open state with `wire:model` to a boolean property.

### Common usage patterns

<code-snippet name="Button component" lang="blade">
<!-- Basic button -->
<x-artisanpack-button>Click Me</x-artisanpack-button>

<!-- variant: solid, soft, outline, ghost, link. color: primary, secondary, accent, neutral, success, warning, error, info. -->
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

<code-snippet name="Form input component" lang="blade">
<!-- Basic input -->
<x-artisanpack-input
    :label="__( 'Name' )"
    wire:model="name"
    :placeholder="__( 'Enter your name' )"
/>

<!-- The error comes from $errors for the wire:model name (or error-field) -->
<x-artisanpack-input
    :label="__( 'Email' )"
    wire:model="email"
    type="email"
    required
/>

<!-- Leading/trailing icons, prefix/suffix and a hint -->
<x-artisanpack-input
    :label="__( 'Search' )"
    wire:model.live.debounce="search"
    leading-icon="search"
    :hint="__( 'Type to search...' )"
/>
<x-artisanpack-input :label="__( 'Price' )" wire:model="price" type="number" prefix="USD" suffix="/mo" />
</code-snippet>

<code-snippet name="Select component" lang="blade">
<!-- Options are strings or [ 'value' => …, 'label' => …, 'disabled' => … ] entries -->
<x-artisanpack-select
    :label="__( 'Country' )"
    wire:model="country"
    :options="[
        [ 'value' => 'us', 'label' => __( 'United States' ) ],
        [ 'value' => 'ca', 'label' => __( 'Canada' ) ],
    ]"
/>

<!-- Option children render after the options; placeholder="" drops the empty first option -->
<x-artisanpack-select :label="__( 'Plan' )" wire:model="plan" placeholder="">
    <option value="starter">{{ __( 'Starter' ) }}</option>
    <option value="team">{{ __( 'Team' ) }}</option>
</x-artisanpack-select>
</code-snippet>

<code-snippet name="Combobox component" lang="blade">
<!-- One component for single and multiple choice, search and tags (replaces choices, choices-offline and tags) -->
<x-artisanpack-combobox :label="__( 'Assignee' )" wire:model="assignee" :options="$users" />
<x-artisanpack-combobox :label="__( 'Tags' )" wire:model="tags" :options="$tags" multiple allow-create />
</code-snippet>

<code-snippet name="Checkbox component" lang="blade">
<x-artisanpack-checkbox wire:model="terms" :label="__( 'I agree to the terms' )" required />
<x-artisanpack-checkbox
    :label="__( 'Select all' )"
    :checked="$allSelected"
    :indeterminate="$someSelected"
    :description="__( 'Applies to every row on this page.' )"
/>
</code-snippet>

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

<code-snippet name="DropdownMenu component" lang="blade">
<!-- Choosing an item clicks it, so wire:click runs from the keyboard too. Pass an id when the same menu repeats (table rows). -->
<x-artisanpack-dropdown-menu :id="'row-'.$listing->id" :label="__( 'Options' )" align="end">
    <x-artisanpack-dropdown-menu-item value="edit" icon="pencil" shortcut="E" wire:click="edit">{{ __( 'Edit' ) }}</x-artisanpack-dropdown-menu-item>
    <x-artisanpack-dropdown-menu-item type="checkbox" value="grid" :checked="$showGrid" wire:click="$toggle( 'showGrid' )">{{ __( 'Show grid' ) }}</x-artisanpack-dropdown-menu-item>
    <x-artisanpack-dropdown-menu-separator />
    <x-artisanpack-dropdown-menu-item value="delete" icon="trash" color="error" wire:click="delete">{{ __( 'Delete' ) }}</x-artisanpack-dropdown-menu-item>
</x-artisanpack-dropdown-menu>
</code-snippet>

<code-snippet name="Toasts" lang="blade">
<!-- Once per page, in the layout -->
<x-artisanpack-toaster />

<!-- In a Livewire action (use ArtisanPack\LivewireUiComponents\Traits\Toast): -->
<!-- $this->toast( __( 'Listing saved' ), color: 'success' ); -->
<!-- $this->dispatch( 'toast', title: __( 'Listing saved' ), color: 'success' ); -->
<!-- Across a redirect: $this->toast( __( 'Listing saved' ), redirectTo: route( 'listings' ) ); or Toasts::flash( title: … ) -->
</code-snippet>

<code-snippet name="Table component" lang="blade">
<!-- Headers: [ 'key' => 'name', 'label' => __( 'Name' ), 'sortable' => true ]. Bind sort-by to a public sortBy property. -->
<x-artisanpack-table :headers="$headers" :rows="$users" :sort-by="$sortBy" :label="__( 'Users' )">
    @scope('cell_name', $user)
        <strong>{{ $user->name }}</strong>
    @endscope

    @scope('cell_actions', $user)
        <x-artisanpack-button
            wire:click="edit({{ $user->id }})"
            size="sm"
            variant="ghost"
            icon-only
            leading-icon="pencil"
            :aria-label="__( 'Edit' )"
        />
    @endscope
</x-artisanpack-table>
</code-snippet>

### Theming

Everything visual is a design token. Set `data-theme` (`light`, `dark`, `high-contrast`), `data-density` (`compact`, `default`, `comfortable`) or `data-preset` on any element. Container components take `surface="glass"` for glass. Let visitors choose with `<x-artisanpack-theme-toggle />`. Generate a brand preset, checked against WCAG 2.2 AA in every theme, with:

<code-snippet name="Generate a brand preset" lang="bash">
php artisan artisanpack:generate-preset acme --primary="#7c3aed"
</code-snippet>

Then load `resources/css/presets/acme.css` after the design system's CSS and set `data-preset="acme"`. `artisanpack:generate-theme` is deprecated: it writes 2.x DaisyUI themes that nothing in 3.0 reads.

### Icons

Components take icon names: `leading-icon="plus"`, `icon="house"`. Names resolve through the design system's icon registry first: Lucide names in kebab-case, such as `plus`, `search`, `trash`, `pencil`, `circle-alert`, `log-out`. Register more with the `registered_icons` config key or `IconRegistry::register()`.

Names the registry doesn't know fall back to Blade Icons:
- A name without a dot becomes a Heroicon: `o-user` renders `heroicon-o-user`, `s-home` renders `heroicon-s-home`.
- Any other installed set needs a dot after its prefix: Font Awesome is `fas.user` or `fab.github`, not `fa-user` (a dotless `fa-user` looks up `heroicon-fa-user` and renders nothing).

Prefer registry names: they match the React and Vue packages and mirror in right-to-left layouts.

### Best practices

1. **Use the components, not raw markup**, and 3.0 names, not the deprecated aliases.
2. **Style with props and tokens**: `color`, `variant`, `size`, `surface` and `:classes`, and the `--apui-*` tokens (`var(--apui-surface-sunken)`). Never DaisyUI classes.
3. **Bind with `wire:model`**: values on fields, open state on overlays. Add `.live` only when each change must reach the server.
4. **Validation errors show automatically**: fields read `$errors` for their `wire:model` name (or `error-field`). Pass `error` only to override it.
5. **Give list items a `wire:key`** in every `@foreach`.
6. **Show toasts from PHP** with the `Toast` trait (`$this->success( __( 'Saved' ) )`) or `$this->dispatch( 'toast', title: …, color: … )`, and keep one `<x-artisanpack-toaster />` in the layout.
7. **Translate every visible string** with `__()`, including attributes: `:label="__( 'Email' )"`. Built-in strings are `*-label` props (`close-label`, `empty-label`).
8. **Keep accessibility intact**: give icon-only buttons an `aria-label`, keep visible labels on fields, and name destructive actions ("Delete listing", not "OK").

### Resources

- Documentation: https://docs.artisanpackui.dev/documentation/livewire-ui-components/home
- Component reference: https://docs.artisanpackui.dev/documentation/design-system/components
- Repository: https://github.com/ArtisanPack-UI/livewire-ui-components
@endverbatim
