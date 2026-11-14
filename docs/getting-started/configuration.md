---
title: Configuration
menu_order: 2
---

# Configuration

The package works without a config file. To change a setting, publish one:

```bash
php artisan vendor:publish --tag=artisanpack-package-config
```

This writes `config/artisanpack/livewire-ui-components.php`. The package also reads `config/livewire-ui-components.php`, which `--tag=livewire-ui-components.config` publishes. The settings merge in this order, later files winning: the package's defaults, `config/livewire-ui-components.php`, then `config/artisanpack/livewire-ui-components.php`. You only need the keys you change.

Every value is read as `config( 'artisanpack.livewire-ui-components.<key>' )`.

## Settings

| Key | Default | Does |
|---|---|---|
| `prefix` | `'artisanpack'` | The tag prefix: `<x-artisanpack-button>`, `<livewire:artisanpack-calendar>`. See below |
| `route_prefix` | `''` | A URL prefix for the package's routes |
| `uploads.disks` | `['public']` | The disks the upload route may store on. The first is the default |
| `uploads.mimes` | `['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif']` | The image types the upload route accepts. SVG is never accepted |
| `uploads.max_size` | `5120` | The largest upload, in kilobytes |
| `components.spotlight.class` | `'App\Support\Spotlight'` | The class whose `search()` the Spotlight route calls. See [Spotlight](Livewire-Spotlight) |
| `registered_icons` | `[]` | Extra icons for the icon registry. See [Icons](Icons) |
| `assets.stylesheet` | `'default'` | The stylesheet `@artisanpackStyles` prints: `default` (with component styles) or `headless` |
| `assets.path` | `'vendor/artisanpack-ui/design-system'` | Where the prebuilt assets are published, under `public/` |

### The component prefix

All components register under the prefix, joined with a hyphen: with `'prefix' => 'artisanpack'` a button is `<x-artisanpack-button>`. Clear the compiled views after changing it:

```bash
php artisan view:clear
```

Keep the default in 3.0. The package's own views render nested components as `<x-artisanpack-…>` (the buttons in Dialog, Drawer and Alert, the checkboxes, select and pagination in Table, and more), so those components fail to render under any other prefix.

### Routes

The package registers three routes, under `route_prefix` when you set one:

| Route | Name | Used by |
|---|---|---|
| `GET /artisanpack/spotlight` | `artisanpack.spotlight` | Spotlight, when it has no `groups` or `url` |
| `POST /artisanpack/upload` | `artisanpack.upload` | RichTextEditor image uploads. Requires an authenticated user |
| `GET /artisanpack/toogle-sidebar` | `artisanpack.toogle-sidebar` | AppShell, to remember a collapsible sidebar's state in the session |

With `'route_prefix' => 'admin'`, the Spotlight route is `/admin/artisanpack/spotlight`.

## Deprecated settings

`theme_output_path`, `glass` and `design_tokens` configure the deprecated `artisanpack:generate-theme` command, and nothing else reads them. `icons.list_item` and `icons.loading` are no longer read. See [Theming](Theming).
