---
title: Deprecated Aliases
menu_order: 1
---

# Deprecated Aliases

These 2.x tags still render in 3.0, so an app keeps working while you migrate. Each one renders its 3.0 replacement and maps the 2.x attributes onto it. They are deprecated and will be removed in a later major release.

`php artisan artisanpack:upgrade-ui` rewrites them to the new tags for you. See [Upgrading from 2.x](Upgrading).

"Ignored" below means the attribute is accepted, so it doesn't error or leak into the HTML, but it has no effect.

## Renamed tags

These are the same component under its old name. Every attribute works as it does on the new tag.

| 2.x tag | Use instead |
|---|---|
| `<x-artisanpack-colorpicker>` | [`<x-artisanpack-color-picker>`](https://docs.artisanpackui.dev/documentation/design-system/components/color-picker) |
| `<x-artisanpack-datepicker>` | [`<x-artisanpack-date-picker>`](https://docs.artisanpackui.dev/documentation/design-system/components/date-picker) |
| `<x-artisanpack-editor>` | [`<x-artisanpack-rich-text-editor>`](https://docs.artisanpackui.dev/documentation/design-system/components/rich-text-editor) |

## Combobox aliases

### `choices` and `choices-offline`

Render [`<x-artisanpack-combobox>`](https://docs.artisanpackui.dev/documentation/design-system/components/combobox), multiple by default as in 2.x. Use `<x-artisanpack-combobox multiple>` instead.

| 2.x | Maps to |
|---|---|
| `single` | `multiple` off |
| `searchable` (`choices` only) | `remote`: typing calls the Livewire method named in `search-function` (default `search`) with the typed text, after `debounce` and once there are `min-chars` characters. Arguments written as `method(5)` are passed after the text. With `search-model` set, Combobox's own search binding is used instead |
| `icon` | `leading-icon` |
| `no-result-text` | `empty-label` |
| `hint-class`, `error-class` | The `hint` and `error` keys of `classes` |
| `debounce` (`250ms`, `1s`) | Combobox's `debounce` in milliseconds |
| `option-value`, `option-label`, `option-sub-label` | Each option's `value`, `label` and `description` |
| `id` | The combobox's base id |

On `choices-offline`, `searchable` is ignored: filtering is always on.

Ignored: `icon-right`, `inline`, `prefix`, `suffix`, `compact`, `compact-text`, `allow-all`, `allow-all-text`, `remove-all-text`, `option-avatar`, `values-as-string`, `height`, `omit-error`, `first-error-only`, `selection`, `prepend`, `append`; on `choices`, also `lazy-load`, `lazy-load-method`, `lazy-load-modifier` and `has-more-options`; on `choices-offline`, also `min-chars`.

`readonly` is ignored too, and not turned into `disabled`, which would change its meaning: Combobox has no read-only state yet ([ArtisanPack-UI/design-system#120](https://github.com/ArtisanPack-UI/design-system/issues/120)).

### `tags`

Renders a Combobox with `multiple` and `allow-create`. Use `<x-artisanpack-combobox multiple allow-create>` instead.

| 2.x | Maps to |
|---|---|
| `single` | `multiple` off |
| `allow-custom-tags` (on by default) | `allow-create` |
| `custom-tags-text` | `create-label` |
| `searchable` | `remote`, calling `search-function` with the typed text after `min-chars`, as on `choices` |
| `icon`, `no-result-text`, `hint-class`, `error-class`, `debounce` | As on `choices` |
| `option-value`, `option-label`, `option-sub-label` | Each option's `value`, `label` and `description`. The value is the option's label, because 2.x bound the tags' labels, not their ids |

Ignored: `icon-right`, `inline`, `prefix`, `suffix`, `option-avatar`, `height`, `prepend`, `append`, `omit-error`, `first-error-only`, and `readonly` (as on `choices`).

## Overlays and menus

### `modal`

Renders [`<x-artisanpack-dialog>`](https://docs.artisanpackui.dev/documentation/design-system/components/dialog).

| 2.x | Maps to |
|---|---|
| `wire:model` | The open state, as in 2.x |
| `subtitle` | `description` |
| `box-class` | Classes on the panel (the `root` key of `classes`) |
| `actions` slot or attribute | The footer |
| `glass` | `surface="glass"` |
| `persistent` | An alertdialog without a close button, so clicking the backdrop doesn't close it. Escape still does |
| `id` | The dialog's id |

Ignored: `separator`, `without-trap-focus`, `glass-tint`, `glass-tint-opacity`.

### `dropdown`

Renders [`<x-artisanpack-dropdown-menu>`](https://docs.artisanpackui.dev/documentation/design-system/components/dropdown-menu). The default slot renders inside the menu, as in 2.x, and links inside it open normally.

| 2.x | Maps to |
|---|---|
| `label`, or a string `trigger` attribute | The trigger's label |
| `right` | Aligns the menu to the end |
| `glass` | The glass surface |
| `id` | The menu's id |

Ignored: `icon`, `top`, `no-x-anchor`, `glass-tint`, `glass-tint-opacity`.

### `profile`

Renders [`<x-artisanpack-user-menu>`](https://docs.artisanpackui.dev/documentation/design-system/components/user-menu). `title` is the name, `subtitle` the role line and `image` the avatar; `right` aligns the menu to the end. The slot renders as the menu's items. The theme control is hidden, as 2.x had none.

## Form fields

### `radio`

Renders [`<x-artisanpack-radio-group>`](https://docs.artisanpackui.dev/documentation/design-system/components/radio-group).

| 2.x | Maps to |
|---|---|
| `option-value`, `option-label`, `option-hint` | Each option's `value`, `label` and `description` |
| `inline` | `orientation="horizontal"` |
| `card` | `variant="cards"` |
| `hint-class`, `error-class`, `card-class` | The `hint`, `error` and `item` keys of `classes` |
| `id` | The group's id |

Ignored: `omit-error` and `first-error-only`. The group always shows its first error.

### `group`

Renders a RadioGroup with `variant="segmented"` and `orientation="horizontal"`, like the 2.x joined buttons. `option-value`, `option-label`, `hint-class`, `error-class` and `id` map as on `radio`; `omit-error` and `first-error-only` are ignored.

### `select-group`

Renders [`<x-artisanpack-select>`](https://docs.artisanpackui.dev/documentation/design-system/components/select). The 2.x `[ 'Group label' => [ options ] ]` map becomes Select's `[ 'label' => …, 'options' => [ … ] ]` groups, read through `option-value` and `option-label`. `icon` is `leading-icon`; `hint-class` and `error-class` become the `hint` and `error` keys of `classes`. Without a `placeholder` there's no empty option, as in 2.x.

Ignored: `icon-right`, `placeholder-value`, `inline`, `prepend`, `append`, `omit-error`, `first-error-only`.

### `datetime`

Renders [`<x-artisanpack-date-picker>`](https://docs.artisanpackui.dev/documentation/design-system/components/date-picker) and takes every DatePicker prop. `type` picks the mode: `date` (the default, as in 2.x) is a date; `datetime-local` and `time` turn on DatePicker's time mode, valued like `2026-11-12T11:00`. A time needs a date: for a time on its own, use `<x-artisanpack-input type="time">`. Use `<x-artisanpack-date-picker time>` instead.

### `errors`

Renders [`<x-artisanpack-validation-summary>`](https://docs.artisanpackui.dev/documentation/design-system/components/validation-summary). `title` and `only` carry over, and an `id` lands on the root. `description` and `icon` are ignored: the summary shows a title and the errors, and its icon follows `color`.

## Layout and text

### `main`

Renders [`<x-artisanpack-app-shell>`](https://docs.artisanpackui.dev/documentation/design-system/components/app-shell). The `content` slot is the content and the `sidebar` slot the sidebar, whose `collapsible` and `collapse-text` attributes still work. The 2.x `footer` slot renders after the content in the main column.

Ignored: `full-width`, `with-nav`, `collapse-icon`, and the sidebar's `drawer`, `right` and `right-mobile` attributes.

### `nav`

Renders [`<x-artisanpack-navbar>`](https://docs.artisanpackui.dev/documentation/design-system/components/navbar). The `brand` and `actions` slots carry over, and `sticky` keeps its 2.x default of off. `full-width` is ignored: the bar is always full width.

### `header`

Renders [`<x-artisanpack-page-header>`](https://docs.artisanpackui.dev/documentation/design-system/components/page-header). `subtitle` is the description, the title keeps the 2.x default heading level 2, and the `middle` slot renders in the row before the actions.

Ignored: `separator`, `progress-indicator`, `progress-indicator-class`, `with-anchor`, `size`, `icon`, `icon-classes`.

### `widget-grid`

Renders [`<x-artisanpack-grid>`](https://docs.artisanpackui.dev/documentation/design-system/components/grid). `cols` (default 4) is `columns` and `gap` keeps its 2.x default of 6. The grid reflows by `min-item-width` (default `14rem`) instead of Tailwind breakpoints.

### `separator`

Renders [`<x-artisanpack-divider>`](https://docs.artisanpackui.dev/documentation/design-system/components/divider). `vertical` is `orientation="vertical"`. The loading bar (`progress`, `target`), `color` and `image` are ignored: use [Progress](https://docs.artisanpackui.dev/documentation/design-system/components/progress) for a loading bar.

### `subheading`

Renders [`<x-artisanpack-text size="lead">`](https://docs.artisanpackui.dev/documentation/design-system/components/text).
