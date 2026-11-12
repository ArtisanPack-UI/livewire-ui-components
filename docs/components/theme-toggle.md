---
title: ThemeToggle Component
---

# ThemeToggle Component

Lets people choose light, dark, high contrast or system, built on the ArtisanPack UI design system (`.apui-theme-toggle`). A choice sets `data-theme` on `<html>`, the attribute the design tokens switch themes on, and persists in `localStorage`. System follows the operating system's `prefers-color-scheme` live.

## Basic Usage

Put the head snippet in your layout's `<head>`, before the stylesheets, so the stored choice applies before first paint and the page never flashes the wrong theme:

```blade
<head>
    @apuiThemeScript
    @artisanpackStyles
    …
</head>
```

Then put the toggle anywhere:

```blade
<x-artisanpack-theme-toggle />
```

`@apuiThemeScript([ 'storageKey' => 'site-theme', 'default' => 'dark' ])` changes the storage key and the choice without a stored one. Pass the same `storage-key` to the toggles. With Vite's CSP nonce set (`Vite::useCspNonce()`), the script carries it.

## Variants

```blade
{{-- Every choice on the segmented track (the default) --}}
<x-artisanpack-theme-toggle />

{{-- Icons only: each name stays, visually hidden, as the radio's accessible name --}}
<x-artisanpack-theme-toggle icon-only :choices="[ 'light', 'dark', 'system' ]" />

{{-- One button that switches light and dark, for a top bar --}}
<x-artisanpack-theme-toggle variant="icon" size="sm" />

{{-- A Theme button that opens the choices, with System last --}}
<x-artisanpack-theme-toggle variant="menu" size="sm" />
```

## Saving the choice with the account

`wire:model` binds the choice to a Livewire property, so you can store it with the person's settings. The toggle still applies and stores it in the browser.

```blade
<x-artisanpack-theme-toggle variant="menu" wire:model.live="theme" />
```

Without `wire:model` or `value`, the toggle shows the stored choice, else System. A choice dispatches `value-change` (`{ value }`) from the root, and every toggle on the page, and in other tabs, follows it.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | string | — | `light`, `dark`, `high-contrast` or `system`. `wire:model` binds it instead. |
| `variant` | string | `segmented` | `segmented`, `icon` or `menu`. |
| `size` | string | `md` | `sm` or `md`. |
| `choices` | array | all four | The choices segmented and menu offer, always in the order light, dark, high contrast, system. Keep high contrast offered. |
| `icon-only` | bool | `false` | Segmented shows only the icons. |
| `label` | string | `__( 'Theme' )` | Names the radio group and the menu; the menu trigger's text. |
| `light-label` | string | `__( 'Light' )` | |
| `dark-label` | string | `__( 'Dark' )` | |
| `high-contrast-label` | string | `__( 'High contrast' )` | |
| `system-label` | string | `__( 'System' )` | The system choice in the segmented control. |
| `match-system-label` | string | `__( 'Match system' )` | The system choice in the menu. |
| `switch-to-light-label` | string | `__( 'Switch to light theme' )` | The icon button's name while it switches to light. |
| `switch-to-dark-label` | string | `__( 'Switch to dark theme' )` | The icon button's name while it switches to dark. |
| `persist` | bool | `true` | Stores the choice in `localStorage`. |
| `storage-key` | string | `apui-theme` | The `localStorage` key. |
| `classes` | array | `[]` | Extra classes per part: `root`, `list`, `option`, `button`, `trigger`, `menu`, `item`. |

Every other attribute lands on the root. The `ap.livewireUiComponents.themeColors` filter renames the choices everywhere at once (see [extension hooks](../advanced/extension-hooks)).

## Applying a theme from JavaScript

Without a bundler, the prebuilt behavior script registers `apuiThemeToggle`. With one, `applyTheme( 'dark' )`, `readThemeChoice()` and `themeScript()` come from `@artisanpack-ui/behavior`.

## Upgrading from 2.x

- Add `@apuiThemeScript` to `<head>`. The toggle's own inline script, which read `mary-theme` and `mary-class`, is gone, and the stored choice now lives under `apui-theme` (the old keys aren't read).
- The toggle sets the tokens' `data-theme` (`light`, `dark`, `high-contrast`) and no longer writes `class` on `<html>`. `light-theme`, `dark-theme`, `light-class`, `dark-class` and `with-label` are removed and ignored.
- `light` and `dark` still work, as `light-label` and `dark-label`.
- The swap icon is now the `icon` variant; the default is the segmented control, which offers high contrast and System too.
- The `mary-toggle-theme` window event is gone; call `applyTheme()` instead. `theme-changed` and `theme-changed-class` are replaced by `value-change`.
- The `themeColors` filter's payload is `{ light: { label }, dark: { label }, 'high-contrast': { label }, system: { label } }`.

## Accessibility

- Segmented is a `role="radiogroup"` named by `label`, with a `role="radio"` per choice and `aria-checked`. Only the checked choice is in the Tab order; the arrow keys move and apply, following the text direction (in right-to-left pages ArrowLeft moves to the next choice), and Home and End go to the ends.
- Every choice keeps its name, also when only icons show.
- The icon button's name says what it does next, and changes with the theme.
- The menu's choices are `menuitemradio` with `aria-checked`; the trigger shows the current choice's icon.
- Storage is guarded: a private window or blocked storage falls back to the default, without errors.
