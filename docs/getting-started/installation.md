---
title: Installation
menu_order: 1
---

# Installation

## Requirements

- PHP 8.2 or later
- Laravel 12 or 13
- Livewire 3.6 or later, or Livewire 4

## Install the package

```bash
composer require artisanpack-ui/livewire-ui-components
php artisan livewire-ui-components:install
```

The installer:

1. Checks that the app runs Laravel 12 or later.
2. Asks whether to install Livewire Volt too, then runs `composer require livewire/livewire` (plus `livewire/volt` and `php artisan volt:install` if you said yes).
3. Sets up the design system's CSS and JavaScript, either with a bundler or as prebuilt files (see below).
4. On a fresh app, copies a starter layout to `resources/views/components/layouts/app.blade.php`, an `AppBrand` Blade component and a demo page, and **replaces `routes/web.php`** with a route to the demo. It skips this step when it finds Jetstream, Breeze or Flux in `composer.json`. The layout is an AppShell with a sidebar Menu. The sidebar footer holds an icon ThemeToggle, plus a UserMenu (with `hide-theme`, since the toggle owns the theme) for a signed-in user. The layout also has the theme script and the toaster.
5. When it finds Jetstream, Breeze or Flux, checks the [component prefix](Getting-Started-Configuration). An empty prefix gets a warning, because tags like `<x-button>` would collide with the starter kit's. It doesn't publish or change your config file.
6. Clears the compiled views.

Run it on a new app, or commit your work first so you can review what it changed.

### Options

| Option | Does |
|---|---|
| `--assets=bundler` | Installs the design system from npm and imports it in `resources/css/app.css` and `resources/js/app.js`, for Vite |
| `--assets=prebuilt` | Publishes the compiled CSS and JavaScript that ship with the package, for apps without a build step |
| `--tailwind` | With `--assets=bundler`, also installs Tailwind CSS v4 and the design system's Tailwind preset |

Until `@artisanpack-ui/css` and `@artisanpack-ui/behavior` are published to the npm registry, the installer uses the prebuilt files without asking. `--assets=bundler` still runs, with a warning: it only works if you have the packages linked or from a private registry.

If `resources/css/app.css` still loads DaisyUI, the installer warns you. Remove the `@plugin "daisyui"` block, any `@apply` rules that use DaisyUI colors, and `daisyui` from `package.json`.

## Without a bundler

The package ships the design system's stylesheets and the behavior engine's script, compiled. Publish them to `public/`:

```bash
php artisan vendor:publish --tag=artisanpack-design-system-assets --force
```

Then load them in your layout:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace( '_', '-', app()->getLocale() ) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @apuiThemeScript
    @artisanpackStyles
    @artisanpackScripts
</head>
<body>
    {{ $slot }}

    <x-artisanpack-toaster />
</body>
</html>
```

Publish again with `--force` after every package update, so the files match the package version. Each URL the directives print carries a content hash from the published manifest, so browsers pick up the new files.

## With Vite

`--assets=bundler` installs the npm packages as dev dependencies with the package manager you choose (npm, yarn, pnpm or bun) and writes the imports for you. To set it up by hand:

```bash
npm install --save-dev @artisanpack-ui/css @artisanpack-ui/behavior
```

```css
/* resources/css/app.css */
@import '@artisanpack-ui/css';
```

```js
// resources/js/app.js
import apuiBehavior from '@artisanpack-ui/behavior/alpine';

document.addEventListener('alpine:init', () => window.Alpine.plugin(apuiBehavior));
```

Livewire bundles and starts Alpine, so the plugin registers on `alpine:init`. Keep `@vite( [ 'resources/css/app.css', 'resources/js/app.js' ] )` in your layout's `<head>`, with `@apuiThemeScript` before it.

### With Tailwind

When `app.css` already imports Tailwind (Laravel's default does), the installer declares the layer order first, so Tailwind's Preflight never restyles a component and Tailwind utilities still win over component styles:

```css
@layer theme, base, apui, components, utilities;
@import 'tailwindcss';
@import '@artisanpack-ui/css';
```

With `--tailwind`, it also adds the Tailwind preset, which generates utilities from the tokens (`bg-surface-page`, `text-muted`, `rounded-control`), and points Tailwind at the package's views:

```css
@import '@artisanpack-ui/css/tailwind';

@source "../../vendor/artisanpack-ui/livewire-ui-components/resources/views/**/*.php";
@source "../../vendor/artisanpack-ui/livewire-ui-components/src/View/Components/**/*.php";
```

See [With Tailwind](https://docs.artisanpackui.dev/documentation/design-system/getting-started/tailwind) in the design-system documentation.

## The Blade directives

| Directive | Prints | Where |
|---|---|---|
| `@apuiThemeScript` | A small blocking script that applies the visitor's stored theme (`data-theme` on `<html>`) before the first paint, and follows the system's light or dark setting until they choose | First in `<head>`, before any stylesheet |
| `@artisanpackStyles` | A `<link>` to the prebuilt stylesheet set in the `assets.stylesheet` config (`default`) | `<head>` |
| `@artisanpackStyles('headless')` | The [headless](https://docs.artisanpackui.dev/documentation/design-system/theming/headless) stylesheet: tokens, reset and utilities, without component styles | `<head>`, instead of the above |
| `@artisanpackScripts` | The behavior engine's Alpine plugin as one classic script | `<head>`, or anywhere before Livewire's script |

`@artisanpackScripts` must load before Livewire's script, because it registers the engine with Alpine as Alpine starts. Livewire injects its script at the end of `<body>`, so `<head>` is always safe.

`@apuiThemeScript` takes the storage key and the default choice:

```blade
@apuiThemeScript( [ 'storageKey' => 'site-theme', 'default' => 'dark' ] )
```

The default storage key is `apui-theme` and the default choice is `system`. If you change the key, pass the same `storage-key` to every [ThemeToggle](https://docs.artisanpackui.dev/documentation/design-system/components/theme-toggle). See [Theming](Theming).

## The toaster

Toasts show in a toaster region. Put `<x-artisanpack-toaster />` once in your layout to show toasts sent from Livewire, flashed to the session or created from JavaScript. See [Toasts](Livewire-Toasts).

## Next steps

- [Configuration](Getting-Started-Configuration)
- [Livewire Integration](Livewire)
- [Components](Components)
- [Upgrading from 2.x](Upgrading), if you're coming from 2.x
