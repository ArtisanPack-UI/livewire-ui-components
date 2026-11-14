---
title: Theming
menu_order: 5
---

# Theming

Everything visual in the components is a design token, so theming means changing tokens: a theme (light, dark or high contrast), a density, a preset (a named brand look) or single tokens. The design system's [Theming](https://docs.artisanpackui.dev/documentation/design-system/theming) section explains these levels. This page covers the Laravel side: the theme script, the theme components and the preset generator.

## Themes, density and presets

Theme, density and preset are attributes that any element can set for everything inside it:

```blade
<html lang="{{ str_replace( '_', '-', app()->getLocale() ) }}" data-preset="ledger">
```

| Attribute | Values |
|---|---|
| `data-theme` | `light`, `dark`, `high-contrast` |
| `data-density` | `compact`, `default`, `comfortable` |
| `data-preset` | `ledger`, `harbor`, or a preset of your own |

See [Themes](https://docs.artisanpackui.dev/documentation/design-system/theming/themes), [Presets](https://docs.artisanpackui.dev/documentation/design-system/theming/presets) and [Customizing Tokens](https://docs.artisanpackui.dev/documentation/design-system/theming/customizing-tokens).

Glass is a surface, not a theme: container components take `surface="glass"`. See [Glass](https://docs.artisanpackui.dev/documentation/design-system/theming/glass).

## The theme script

`@apuiThemeScript` goes first in your layout's `<head>`. It applies the visitor's stored theme before the first paint, so a dark-mode visitor never sees a flash of the light theme, and follows the system's light or dark setting until they choose:

```blade
<head>
    @apuiThemeScript
    …
</head>
```

It takes the localStorage key and the default choice (`light`, `dark`, `high-contrast` or `system`):

```blade
@apuiThemeScript( [ 'storageKey' => 'site-theme', 'default' => 'dark' ] )
```

## Letting visitors choose

[ThemeToggle](https://docs.artisanpackui.dev/documentation/design-system/components/theme-toggle) offers Light, Dark, High contrast and System. A choice sets `data-theme` on `<html>`, is stored in localStorage and dispatches `value-change`:

```blade
<x-artisanpack-theme-toggle />
<x-artisanpack-theme-toggle variant="icon" />
<x-artisanpack-theme-toggle variant="menu" />
```

`variant` is `segmented` (the default), `icon` (a button that switches between light and dark) or `menu`. If you changed the storage key in `@apuiThemeScript`, pass the same one as `storage-key`. To keep the choice on the server too, such as in a user's settings, bind it with `wire:model`.

To rename the choices everywhere at once, use the `ap.livewireUiComponents.themeColors` filter. See [Extension Hooks](Advanced-Extension-Hooks).

[ThemePreview](https://docs.artisanpackui.dev/documentation/design-system/components/theme-preview) shows selectable cards with a live miniature of each theme or preset, drawn with the real tokens. It's a radio group, so `wire:model` binds the chosen value:

```blade
<x-artisanpack-theme-preview
    wire:model.live="preset"
    :label="__( 'Brand' )"
    :options="[
        [ 'value' => 'default', 'label' => __( 'Default' ) ],
        [ 'value' => 'ledger', 'label' => __( 'Ledger' ), 'preset' => 'ledger' ],
        [ 'value' => 'harbor', 'label' => __( 'Harbor' ), 'preset' => 'harbor' ],
    ]"
/>
```

Each option takes a `value`, a `label`, and the `theme` and `preset` its miniature shows. Apply the stored choice yourself, for example as `data-preset` on `<html>`.

## Your own brand preset

Generate a preset from your brand colors:

```bash
php artisan artisanpack:generate-preset acme --primary="#7c3aed"
```

It writes `resources/css/presets/acme.css`. The generator rebuilds the color ramps around your colors, checks every contrast pair in every theme, and writes nothing if any pair fails WCAG 2.2 AA. It prints the ramp step each of your colors landed on.

| Option | Default | Does |
|---|---|---|
| `name` argument | asked for | The preset's name, used as `data-preset="name"` |
| `--primary` | asked for | The primary brand color, as a hex code |
| `--secondary`, `--accent` | unchanged | Rebuild the secondary and accent ramps |
| `--neutral` | `tinted` | `tinted` tints the grays toward the primary, `pure` makes them pure gray, `keep` leaves them |
| `--font-sans`, `--font-mono`, `--font-display` | unchanged | Comma-separated font families |
| `--description` | | A description for the preset |
| `--format` | `css` | `css`, `php` or `json` |
| `--output` | `resources/css/presets/<name>.<format>` | The file to write |
| `--force` | | Overwrite an existing file without asking |

Without a name or `--primary`, the command asks for them.

Load the preset after the design system's CSS and switch it on:

```css
/* resources/css/app.css */
@import '@artisanpack-ui/css';
@import './presets/acme.css';
```

```blade
<html data-preset="acme">
```

Without a bundler, write the file under `public/` with `--output=public/css/presets/acme.css` and link it after `@artisanpackStyles`.

See [Brand Presets](https://docs.artisanpackui.dev/documentation/design-system/theming/brand-presets) for how the generator keeps contrast.

## Bringing your own styles

`@artisanpackStyles('headless')`, or `@import '@artisanpack-ui/css/headless'` with a bundler, loads the tokens, reset and utilities without the component styles. The markup, ARIA and behavior stay. See [Headless](https://docs.artisanpackui.dev/documentation/design-system/theming/headless).

To restyle one part of one component, pass classes through `:classes`. See [Component Markup](https://docs.artisanpackui.dev/documentation/design-system/styling/component-markup).

## `artisanpack:generate-theme` is deprecated

`php artisan artisanpack:generate-theme` wrote Tailwind palettes and DaisyUI theme variables in 2.x. It still runs in 3.0 and warns that it's deprecated, but nothing in 3.0 reads its output. Use `artisanpack:generate-preset` instead. The 2.x theme builder page and its `--preview` URL are gone.
