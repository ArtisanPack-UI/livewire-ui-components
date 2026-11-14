---
title: Icons
menu_order: 6
---

# Icons

Components take icons by name: `leading-icon="plus"` on a Button, `icon="user"` on a menu item, `'icon' => 'house'` in a Spotlight item. A name resolves through an icon registry that React and Vue share, so the same name draws the same SVG in every stack. The default set is a subset of [Lucide](https://lucide.dev), with Lucide's kebab-case names (`chevron-down`, `circle-alert`, `log-out`). See [Icons](https://docs.artisanpackui.dev/documentation/design-system/foundations/icons) in the design-system documentation for how icons are drawn and sized, and the [Icon](https://docs.artisanpackui.dev/documentation/design-system/components/icon) component for a standalone icon.

In Livewire UI Components the registry is the PHP class `ArtisanPack\LivewireUiComponents\Support\IconRegistry`.

## Adding or replacing icons

Each icon is the inner markup of a 24×24 SVG drawn with `stroke="currentColor"`; the registry wraps it in the shared `<svg>`. A name you register replaces a default icon of the same name.

In the config, under `registered_icons`:

```php
// config/artisanpack/livewire-ui-components.php
'registered_icons' => [
    'rocket' => '<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2" />',
],
```

Or in a service provider's `boot()` method:

```php
use ArtisanPack\LivewireUiComponents\Support\IconRegistry;

IconRegistry::register( [
    'brand-logo' => '<path d="M4 4h16v16H4z" /><circle cx="12" cy="12" r="4" />',
] );
```

Icons registered at runtime win over the config, which wins over the defaults.

**Registered icons are trusted markup**: they render unescaped. The registry only accepts the shape elements `path`, `circle`, `rect`, `line`, `polyline`, `polygon`, `ellipse` and `g`, and rejects event handlers, `href`, `xlink:href`, `style`, character references and `javascript:`, `vbscript:` and `data:` URLs. A body that fails the check throws an `InvalidArgumentException`, and nothing in that call is registered. Never register icons from user or CMS input.

## Directional icons

Icons that point a direction mirror under `dir="rtl"`. `arrow-left`, `arrow-right`, `chevron-left` and `chevron-right` do by default. Add your own:

```php
IconRegistry::registerDirectional( [ 'reply', 'brand-arrow' ] );
```

## Other icon sets

Names the registry doesn't know fall back to [Blade Icons](https://github.com/driesvints/blade-icons), so 2.x icon names keep rendering:

| You write | Renders |
|---|---|
| `o-user`, `s-home` | Heroicons outline and solid (`heroicon-o-user`), which the package installs |
| `fas.user`, `fab.github` | Any installed Blade Icons set, with a dot after the set's prefix (`fas-user`). Font Awesome comes with the package |

The `ap.livewireUiComponents.iconAlias` filter can rename an icon before the fallback looks it up. See [Extension Hooks](Advanced-Extension-Hooks).

The fallback is for migrating. Prefer registry names: they're the same in React and Vue, and they mirror in right-to-left layouts.

## Reading the registry

| Method | Returns |
|---|---|
| `IconRegistry::has( 'rocket' )` | Whether a name is registered |
| `IconRegistry::names()` | Every registered name |
| `IconRegistry::svg( 'rocket' )` | The full `<svg>` for a registered name, or an empty string |
| `IconRegistry::render( 'o-user' )` | The `<svg>` for any name: the registry first, then Blade Icons |
| `IconRegistry::isDirectional( 'chevron-left' )` | Whether a name mirrors under RTL |
