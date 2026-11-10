---
title: Design Token Presets
---

# Design Token Presets

From 3.0, theming is built on the ArtisanPack UI Design System tokens. Colors, type, radii and spacing are `--apui-*` custom properties, and three attributes switch them on any element:

| Attribute | Values |
|---|---|
| `data-theme` | `light`, `dark`, `high-contrast` |
| `data-density` | `compact`, `default`, `comfortable` |
| `data-preset` | `ledger`, `harbor`, or a brand preset you generate |

A preset reassigns token values only, so every component follows it without a markup or CSS change.

## Generating a brand preset

```bash
php artisan artisanpack:generate-preset acme --primary="#7c3aed"
```

This writes `resources/css/presets/acme.css`. Import it after the design system CSS and apply it:

```html
<html data-preset="acme">
```

| Option | Default | Does |
|---|---|---|
| `name` | asked | The `data-preset` value: lowercase letters, digits and single hyphens |
| `--primary` | asked | Rebuilds the primary ramp around this hex color |
| `--secondary`, `--accent` | unchanged | Rebuild the secondary and accent ramps |
| `--neutral` | `tinted` | `tinted` tints the grays toward the primary; `pure` makes them pure gray; `keep` leaves them |
| `--font-sans`, `--font-mono`, `--font-display` | unchanged | Comma-separated font families |
| `--format` | `css` | `css`, `php` (an array) or `json` |
| `--output` | `resources/css/presets/NAME.FORMAT` | Where to write |
| `--force` | | Overwrite an existing file |

### Contrast is checked for you

Each generated ramp step keeps your color's hue and chroma but matches the luminance of the default ramp's step. Contrast depends only on luminance, so every pair that passes in the default themes still passes. Your exact color lands on the step nearest its luminance when that also passes, and the command reports which step it used.

Before writing anything, the command checks every WCAG 2.2 AA pair in light, dark and high contrast: text at 4.5:1 on every surface, control edges and focus at 3:1, and glass text over black, white, red and blue backdrops. If any pair fails, it lists the failures and writes nothing.

The generator is a port of the design system's `npx apui-generate-preset` and produces identical output for the same inputs.

## From PHP

```php
use ArtisanPack\LivewireUiComponents\Theming\PresetGenerator;

$preset = app(PresetGenerator::class)->generate([
    'name'    => 'acme',
    'primary' => '#7c3aed',
    'accent'  => '#f59e0b',
]);

if ($preset->passes()) {
    file_put_contents(resource_path('css/presets/acme.css'), $preset->css());
}

$preset->toArray(); // Every affected token with its CSS and resolved values.
```

## High contrast and system preferences

High contrast is a token theme: `data-theme="high-contrast"`. `TokenThemes` builds the attributes, checks contrast, and writes CSS that follows the operating system when the page sets no theme:

```php
use ArtisanPack\LivewireUiComponents\Theming\TokenThemes;

$themes = app(TokenThemes::class);
$themes->attributes('high-contrast', 'compact'); // ['data-theme' => 'high-contrast', 'data-density' => 'compact']
$themes->prefersContrastCss(); // Applies high contrast under prefers-contrast: more
$themes->prefersDarkCss();     // Applies dark under prefers-color-scheme: dark
$themes->failures();           // [] when every pair passes
```

## Glass

Glass is a surface, not a preset: `data-surface="glass"` on a Card, Dialog, DropdownMenu, Toast, Navbar, Alert or Table. It keeps an 80% opacity floor and its own muted text color, so text keeps 4.5:1 over any backdrop. It falls back to an opaque surface under reduced transparency, without `backdrop-filter` and in high contrast.

```php
use ArtisanPack\LivewireUiComponents\Theming\GlassSurface;

app(GlassSurface::class)->attributes('card'); // ['data-surface' => 'glass']
app(GlassSurface::class)->tokens('dark');     // The glass tokens, resolved for dark
```

## Replacing the 2.x theming

| 2.x | 3.0 |
|---|---|
| `artisanpack:generate-theme` | `artisanpack:generate-preset` |
| `ColorGenerator` | `PresetGenerator`. Components take the eight semantic colors only; arbitrary Tailwind and hex colors are dropped |
| `GlassPresets` (`glass-frosted-light`, …) | `GlassSurface`, `data-surface="glass"` |
| `HighContrastTheme` | `TokenThemes`, `data-theme="high-contrast"` |

The 2.x classes and command still work and are marked deprecated. They're removed once the components that use them are rebuilt on the design system.

## The token map

The package reads the design system's token map from `resources/design-system/tokens.php`, a copy of `@artisanpack-ui/tokens/php`. Pass another path to read a newer build:

```php
use ArtisanPack\LivewireUiComponents\Theming\DesignTokens;
use ArtisanPack\LivewireUiComponents\Theming\PresetGenerator;

$generator = new PresetGenerator(DesignTokens::load(base_path('node_modules/@artisanpack-ui/tokens/generated/tokens.php')));
```
