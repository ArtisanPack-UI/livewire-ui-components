---
title: Components
menu_order: 3
---

# Components

Livewire UI Components ships the design system's 78 components as Blade components. Each one has a page in the design-system documentation with a Blade tab, live examples, and tables of its props, parts, slots and events. The links below go there.

## Writing them in Blade

- **Tags** are `<x-artisanpack-{name}>`, with the name in kebab-case: `<x-artisanpack-dropdown-menu>`. The prefix comes from the [configuration](Getting-Started-Configuration).
- **Props** are kebab-case attributes: `leadingIcon` in React and Vue is `leading-icon` in Blade. Bind PHP values with a colon: `:options="$countries"`, `:label="__( 'Country' )"`.
- **Per-part classes** go in `:classes`, keyed by part: `:classes="[ 'label' => 'sr-only', 'control' => 'font-mono' ]"`. A plain `class` lands on the component's root (on overlays, on the panel). See [Component Markup](https://docs.artisanpackui.dev/documentation/design-system/styling/component-markup).
- **Values and open state** bind with `wire:model`, including its modifiers. See [Livewire Integration](Livewire).
- **Slots** are named Blade slots: `<x-slot:footer>`. Slots that receive a row or an item are scoped slots, written with `@scope`: `@scope( 'cell_name', $user )`.
- **Events** are DOM events on the component, such as `open-change` and `value-change`. Listen with `x-on:value-change="…"`.
- **Strings** the components render on their own (close buttons, empty states, screen-reader announcements) are props ending in `-label`, such as `close-label` and `empty-label`. Their defaults go through `__()`, so your translation files cover them.

## Actions

| Component | Blade tag |
|---|---|
| [Button](https://docs.artisanpackui.dev/documentation/design-system/components/button) | `<x-artisanpack-button>` |
| [Link](https://docs.artisanpackui.dev/documentation/design-system/components/link) | `<x-artisanpack-link>` |
| [Clipboard](https://docs.artisanpackui.dev/documentation/design-system/components/clipboard) | `<x-artisanpack-clipboard>` |
| [Swap](https://docs.artisanpackui.dev/documentation/design-system/components/swap) | `<x-artisanpack-swap>` |

## Forms

| Component | Blade tag |
|---|---|
| [Form](https://docs.artisanpackui.dev/documentation/design-system/components/form) | `<x-artisanpack-form>` |
| [Fieldset](https://docs.artisanpackui.dev/documentation/design-system/components/fieldset) | `<x-artisanpack-fieldset>` |
| [Input](https://docs.artisanpackui.dev/documentation/design-system/components/input) | `<x-artisanpack-input>` |
| [Password](https://docs.artisanpackui.dev/documentation/design-system/components/password) | `<x-artisanpack-password>` |
| [Pin](https://docs.artisanpackui.dev/documentation/design-system/components/pin) | `<x-artisanpack-pin>` |
| [Textarea](https://docs.artisanpackui.dev/documentation/design-system/components/textarea) | `<x-artisanpack-textarea>` |
| [Select](https://docs.artisanpackui.dev/documentation/design-system/components/select) | `<x-artisanpack-select>` |
| [Combobox](https://docs.artisanpackui.dev/documentation/design-system/components/combobox) | `<x-artisanpack-combobox>` |
| [Checkbox](https://docs.artisanpackui.dev/documentation/design-system/components/checkbox) | `<x-artisanpack-checkbox>` |
| [CheckboxGroup](https://docs.artisanpackui.dev/documentation/design-system/components/checkbox-group) | `<x-artisanpack-checkbox-group>` |
| [RadioGroup](https://docs.artisanpackui.dev/documentation/design-system/components/radio-group) | `<x-artisanpack-radio-group>` |
| [Toggle](https://docs.artisanpackui.dev/documentation/design-system/components/toggle) | `<x-artisanpack-toggle>` |
| [Range](https://docs.artisanpackui.dev/documentation/design-system/components/range) | `<x-artisanpack-range>` |
| [Rating](https://docs.artisanpackui.dev/documentation/design-system/components/rating) | `<x-artisanpack-rating>` |
| [ColorPicker](https://docs.artisanpackui.dev/documentation/design-system/components/color-picker) | `<x-artisanpack-color-picker>` |
| [DatePicker](https://docs.artisanpackui.dev/documentation/design-system/components/date-picker) | `<x-artisanpack-date-picker>` |
| [File](https://docs.artisanpackui.dev/documentation/design-system/components/file) | `<x-artisanpack-file>` |
| [Signature](https://docs.artisanpackui.dev/documentation/design-system/components/signature) | `<x-artisanpack-signature>` |
| [ValidationSummary](https://docs.artisanpackui.dev/documentation/design-system/components/validation-summary) | `<x-artisanpack-validation-summary>` |

## Editors and content

| Component | Blade tag |
|---|---|
| [RichTextEditor](https://docs.artisanpackui.dev/documentation/design-system/components/rich-text-editor) | `<x-artisanpack-rich-text-editor>` |
| [CodeEditor](https://docs.artisanpackui.dev/documentation/design-system/components/code-editor) | `<x-artisanpack-code-editor>` |
| [Markdown](https://docs.artisanpackui.dev/documentation/design-system/components/markdown) | `<x-artisanpack-markdown>` |
| [Code](https://docs.artisanpackui.dev/documentation/design-system/components/code) | `<x-artisanpack-code>` |
| [Kbd](https://docs.artisanpackui.dev/documentation/design-system/components/kbd) | `<x-artisanpack-kbd>` |
| [Diff](https://docs.artisanpackui.dev/documentation/design-system/components/diff) | `<x-artisanpack-diff>` |
| [Heading](https://docs.artisanpackui.dev/documentation/design-system/components/heading) | `<x-artisanpack-heading>` |
| [Text](https://docs.artisanpackui.dev/documentation/design-system/components/text) | `<x-artisanpack-text>` |
| [Divider](https://docs.artisanpackui.dev/documentation/design-system/components/divider) | `<x-artisanpack-divider>` |
| [Icon](https://docs.artisanpackui.dev/documentation/design-system/components/icon) | `<x-artisanpack-icon>` |

## Data display

| Component | Blade tag |
|---|---|
| [Badge](https://docs.artisanpackui.dev/documentation/design-system/components/badge) | `<x-artisanpack-badge>` |
| [Avatar](https://docs.artisanpackui.dev/documentation/design-system/components/avatar) | `<x-artisanpack-avatar>` |
| [Stat](https://docs.artisanpackui.dev/documentation/design-system/components/stat) | `<x-artisanpack-stat>` |
| [KpiCard](https://docs.artisanpackui.dev/documentation/design-system/components/kpi-card) | `<x-artisanpack-kpi-card>` |
| [Sparkline](https://docs.artisanpackui.dev/documentation/design-system/components/sparkline) | `<x-artisanpack-sparkline>` |
| [Table](https://docs.artisanpackui.dev/documentation/design-system/components/table) | `<x-artisanpack-table>` |
| [Chart](https://docs.artisanpackui.dev/documentation/design-system/components/chart) | `<x-artisanpack-chart>` |
| [Timeline](https://docs.artisanpackui.dev/documentation/design-system/components/timeline) | `<x-artisanpack-timeline>` |
| [ListItem](https://docs.artisanpackui.dev/documentation/design-system/components/list-item) | `<x-artisanpack-list-item>` |
| [Calendar](https://docs.artisanpackui.dev/documentation/design-system/components/calendar) | `<livewire:artisanpack-calendar>`, `<x-artisanpack-calendar>` |

## Feedback

| Component | Blade tag |
|---|---|
| [Alert](https://docs.artisanpackui.dev/documentation/design-system/components/alert) | `<x-artisanpack-alert>` |
| [Toast](https://docs.artisanpackui.dev/documentation/design-system/components/toast) | `<x-artisanpack-toast>` |
| [Loading](https://docs.artisanpackui.dev/documentation/design-system/components/loading) | `<x-artisanpack-loading>` |
| [Progress](https://docs.artisanpackui.dev/documentation/design-system/components/progress) | `<x-artisanpack-progress>` |
| [ProgressRadial](https://docs.artisanpackui.dev/documentation/design-system/components/progress-radial) | `<x-artisanpack-progress-radial>` |
| [Skeleton](https://docs.artisanpackui.dev/documentation/design-system/components/skeleton) | `<x-artisanpack-skeleton>` |
| [StreamableContent](https://docs.artisanpackui.dev/documentation/design-system/components/streamable-content) | `<x-artisanpack-streamable-content>` |
| [EmptyState](https://docs.artisanpackui.dev/documentation/design-system/components/empty-state) | `<x-artisanpack-empty-state>` |
| [ErrorState](https://docs.artisanpackui.dev/documentation/design-system/components/error-state) | `<x-artisanpack-error-state>` |

## Overlays

| Component | Blade tag |
|---|---|
| [Dialog](https://docs.artisanpackui.dev/documentation/design-system/components/dialog) | `<x-artisanpack-dialog>` |
| [Drawer](https://docs.artisanpackui.dev/documentation/design-system/components/drawer) | `<x-artisanpack-drawer>` |
| [Popover](https://docs.artisanpackui.dev/documentation/design-system/components/popover) | `<x-artisanpack-popover>` |
| [Tooltip](https://docs.artisanpackui.dev/documentation/design-system/components/tooltip) | `<x-artisanpack-tooltip>` |
| [DropdownMenu](https://docs.artisanpackui.dev/documentation/design-system/components/dropdown-menu) | `<x-artisanpack-dropdown-menu>` |
| [Spotlight](https://docs.artisanpackui.dev/documentation/design-system/components/spotlight) | `<x-artisanpack-spotlight>` |

## Navigation

| Component | Blade tag |
|---|---|
| [Navbar](https://docs.artisanpackui.dev/documentation/design-system/components/navbar) | `<x-artisanpack-navbar>` |
| [Menu](https://docs.artisanpackui.dev/documentation/design-system/components/menu) | `<x-artisanpack-menu>` |
| [UserMenu](https://docs.artisanpackui.dev/documentation/design-system/components/user-menu) | `<x-artisanpack-user-menu>` |
| [Tabs](https://docs.artisanpackui.dev/documentation/design-system/components/tabs) | `<x-artisanpack-tabs>` |
| [Steps](https://docs.artisanpackui.dev/documentation/design-system/components/steps) | `<x-artisanpack-steps>` |
| [Pagination](https://docs.artisanpackui.dev/documentation/design-system/components/pagination) | `<x-artisanpack-pagination>` |
| [Breadcrumbs](https://docs.artisanpackui.dev/documentation/design-system/components/breadcrumbs) | `<x-artisanpack-breadcrumbs>` |

## Disclosure

| Component | Blade tag |
|---|---|
| [Accordion](https://docs.artisanpackui.dev/documentation/design-system/components/accordion) | `<x-artisanpack-accordion>` |
| [Collapse](https://docs.artisanpackui.dev/documentation/design-system/components/collapse) | `<x-artisanpack-collapse>` |

## Layout

| Component | Blade tag |
|---|---|
| [AppShell](https://docs.artisanpackui.dev/documentation/design-system/components/app-shell) | `<x-artisanpack-app-shell>` |
| [PageHeader](https://docs.artisanpackui.dev/documentation/design-system/components/page-header) | `<x-artisanpack-page-header>` |
| [Card](https://docs.artisanpackui.dev/documentation/design-system/components/card) | `<x-artisanpack-card>` |
| [Grid](https://docs.artisanpackui.dev/documentation/design-system/components/grid) | `<x-artisanpack-grid>` |
| [Stack](https://docs.artisanpackui.dev/documentation/design-system/components/stack) | `<x-artisanpack-stack>` |

## Media

| Component | Blade tag |
|---|---|
| [ImageGallery](https://docs.artisanpackui.dev/documentation/design-system/components/image-gallery) | `<x-artisanpack-image-gallery>` |
| [ImageLibrary](https://docs.artisanpackui.dev/documentation/design-system/components/image-library) | `<x-artisanpack-image-library>` |
| [Carousel](https://docs.artisanpackui.dev/documentation/design-system/components/carousel) | `<x-artisanpack-carousel>` |
| [ImageSlider](https://docs.artisanpackui.dev/documentation/design-system/components/image-slider) | `<x-artisanpack-image-slider>` |

## Theme

| Component | Blade tag |
|---|---|
| [ThemeToggle](https://docs.artisanpackui.dev/documentation/design-system/components/theme-toggle) | `<x-artisanpack-theme-toggle>` |
| [ThemePreview](https://docs.artisanpackui.dev/documentation/design-system/components/theme-preview) | `<x-artisanpack-theme-preview>` |
## Child components

These tags only make sense inside a parent. The parent's page documents them.

| Tag | Inside |
|---|---|
| `<x-artisanpack-accordion-item>` | [Accordion](https://docs.artisanpackui.dev/documentation/design-system/components/accordion) |
| `<x-artisanpack-avatar-group>` | A row of overlapping [Avatars](https://docs.artisanpackui.dev/documentation/design-system/components/avatar) |
| `<x-artisanpack-breadcrumbs-item>` | [Breadcrumbs](https://docs.artisanpackui.dev/documentation/design-system/components/breadcrumbs) |
| `<x-artisanpack-dropdown-menu-item>`, `-group`, `-separator` | [DropdownMenu](https://docs.artisanpackui.dev/documentation/design-system/components/dropdown-menu) |
| `<x-artisanpack-list>` | The container for [ListItem](https://docs.artisanpackui.dev/documentation/design-system/components/list-item) rows |
| `<x-artisanpack-menu-item>`, `<x-artisanpack-menu-sub>`, `<x-artisanpack-menu-title>`, `<x-artisanpack-menu-separator>` | [Menu](https://docs.artisanpackui.dev/documentation/design-system/components/menu) |
| `<x-artisanpack-navbar-item>` | [Navbar](https://docs.artisanpackui.dev/documentation/design-system/components/navbar) |
| `<x-artisanpack-step>` | [Steps](https://docs.artisanpackui.dev/documentation/design-system/components/steps) |
| `<x-artisanpack-tab>` | [Tabs](https://docs.artisanpackui.dev/documentation/design-system/components/tabs) |
| `<x-artisanpack-timeline-item>` | [Timeline](https://docs.artisanpackui.dev/documentation/design-system/components/timeline) |
| `<x-artisanpack-toaster>` | The region that shows [Toasts](https://docs.artisanpackui.dev/documentation/design-system/components/toast). Put it once in your layout |

## Livewire guides

Some components lean on Livewire or Laravel in ways the component pages don't cover:

- [Toasts](Livewire-Toasts): the `Toast` trait and toasts from PHP.
- [Spotlight](Livewire-Spotlight): where its search results come from.
- [Calendar](Livewire-Calendar): the `<livewire:artisanpack-calendar>` component.
- [Tables](Livewire-Tables): scoped slots, sorting, selection and exporting.
- [Media and Uploads](Livewire-Media-And-Uploads): ImageLibrary with `WithMediaSync`, File and RichTextEditor uploads.

## Deprecated tags

Most 2.x tags still render in 3.0 as aliases of their 3.0 replacements. See [Deprecated Aliases](Components-Deprecated-Aliases).
