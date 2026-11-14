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

All components register under the prefix, joined with a hyphen: with `'prefix' => 'ui'` a button is `<x-ui-button>` and the calendar is `<livewire:ui-calendar>`. A trailing hyphen is ignored, so `'ui-'` works the same. An empty prefix registers the bare names, such as `<x-button>`. Clear the compiled views after changing it:

```bash
php artisan view:clear
```

Every component also stays registered as `<x-artisanpack-…>`, whatever the prefix. The package's own views render nested components under that name, such as the buttons in Dialog and Alert or the checkboxes in Table, so they work under any prefix.

With no prefix, tags such as `<x-button>` can collide with components from your app or a starter kit. When the installer finds Jetstream, Breeze or Flux, it warns you if the prefix is empty. It never changes your config file.

### Routes

The package registers three routes, under `route_prefix` when you set one:

| Route | Name | Used by |
|---|---|---|
| `GET /artisanpack/spotlight` | `artisanpack.spotlight` | Spotlight, when it has no `groups` or `url` |
| `POST /artisanpack/upload` | `artisanpack.upload` | RichTextEditor image uploads. Requires an authenticated user |
| `GET /artisanpack/toogle-sidebar` | `artisanpack.toogle-sidebar` | AppShell, to remember a collapsible sidebar's state in the session |

With `'route_prefix' => 'admin'`, the Spotlight route is `/admin/artisanpack/spotlight`.

## Deprecated settings

`theme_output_path`, `glass` and `design_tokens` configure the deprecated `artisanpack:generate-theme` command, and nothing else reads them. The 2.x `icons.list_item` and `icons.loading` keys are gone; delete them from a published config. See [Theming](Theming).
