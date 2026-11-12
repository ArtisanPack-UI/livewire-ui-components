---
title: ThemePreview Component
---

# ThemePreview Component

Selectable cards that show a live miniature of each theme or preset, built on the ArtisanPack UI design system (`.apui-theme-preview`, `.apui-theme-card`). Each miniature is drawn with the real tokens: it carries `data-theme` and `data-preset`, so it stays accurate after a rebrand. Livewire only.

## Basic Usage

```blade
<x-artisanpack-theme-preview
    wire:model.live="theme"
    :label="__( 'Appearance' )"
    :options="[
        [ 'value' => 'light', 'label' => __( 'Light' ) ],
        [ 'value' => 'dark', 'label' => __( 'Dark' ) ],
        [ 'value' => 'high-contrast', 'label' => __( 'High contrast' ) ],
        [ 'value' => 'system', 'label' => __( 'System' ) ],
    ]"
/>
```

A System card shows light and dark halves. To apply the chosen theme to the page as well, use a [ThemeToggle](theme-toggle) or call `applyTheme()` from `@artisanpack-ui/behavior`.

## Presets

```blade
<x-artisanpack-theme-preview
    wire:model.live="preset"
    :label="__( 'Style preset' )"
    :options="[
        [ 'value' => 'strata', 'label' => __( 'Strata' ) ],
        [ 'value' => 'ledger', 'label' => __( 'Ledger' ), 'preset' => 'ledger' ],
        [ 'value' => 'harbor', 'label' => __( 'Harbor' ), 'preset' => 'harbor' ],
    ]"
/>
```

A brand preset from `artisanpack:generate-preset` works the same way once its CSS is loaded.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `options` | array | `[]` | The cards: `{ value, label, theme, preset, disabled }`. `theme` is `light`, `dark`, `high-contrast` or `system`; without it, a `value` that names a theme is used, else `light`. Options without a value are skipped. |
| `value` | string | — | The chosen option's value. `wire:model` binds it instead. |
| `name` | string | — | The radios' name. Defaults to the `wire:model` property, else a generated one. |
| `label` | string | `__( 'Theme' )` | Names the radio group. |
| `classes` | array | `[]` | Extra classes per part: `root`, `card`, `frame`, `miniature`, `label`, `box`, `control`, `indicator`, `text`. |

`class` lands on the root. Every other attribute, `wire:model` included, lands on every radio, so it binds natively.

## Upgrading from 2.x

`<livewire:theme-preview>`, the theme builder tool, and its `/artisanpack/theme-preview` route are removed. Build brand presets with `php artisan artisanpack:generate-preset`; `<x-artisanpack-theme-preview>` is now this component.

## Accessibility

- A `role="radiogroup"` named by `label`, of native radios sharing one name, so the platform supplies roving focus and the arrow keys, following the text direction.
- The miniature is decorative (`aria-hidden`); the label carries the name.
- The checked card shows a primary edge and ring on its frame and a filled radio, not color alone; the focus ring shows on the frame.
