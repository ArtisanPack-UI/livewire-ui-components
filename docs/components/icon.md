---
title: Icon Component
---

# Icon Component

One icon from the design-system icon registry (`.apui-icon`): a Lucide glyph drawn at 14, 16, 20 or 24px in `currentColor`, plain or on a soft-colored tile. Icons are decorative unless they have a `label`, and directional icons (arrows, chevrons) mirror in right-to-left pages.

## Basic Usage

```blade
<x-artisanpack-icon name="house" />
<x-artisanpack-icon name="house" size="xl" />
<x-artisanpack-icon name="circle-alert" color="error" :label="__( 'Payment failed' )" />
```

`name` is a Lucide name in kebab-case from the registry (`house`, `arrow-right`, `circle-alert`). `size` is `sm`, `md` (the default), `lg` or `xl`. `color` draws the icon in a color's text tone; unset, it takes the color of the text around it.

## Tiles

`variant="tile"` sets the icon on a soft-colored rounded square (32, 40, 48 or 56px for `sm` to `xl`). It is neutral unless you give it a `color`.

```blade
<x-artisanpack-icon name="wallet" variant="tile" color="primary" />
<x-artisanpack-icon name="trending-up" variant="tile" color="success" size="lg" />
```

## Your own icons

Put an `<svg>` in the slot to draw an icon that isn't registered. Keep it on a 24px grid and draw it in `currentColor`; it takes the same size.

```blade
<x-artisanpack-icon variant="tile" :label="__( 'Spreadsheet' )">
	<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
		<rect width="18" height="18" x="3" y="3" rx="2" />
		<path d="M3 9h18M3 15h18M9 3v18" />
	</svg>
</x-artisanpack-icon>
```

To use a name everywhere, register it once, for example in a service provider. Only register trusted, static SVG shapes, never user input:

```php
use ArtisanPack\LivewireUiComponents\Support\IconRegistry;

IconRegistry::register( [ 'send' => '<path d="m22 2-7 20-4-9-9-4Z" /><path d="M22 2 11 13" />' ] );
IconRegistry::registerDirectional( [ 'send' ] ); // Mirror it in RTL.
```

The `registered_icons` config key does the same.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `name` | string | `null` | A registered icon name. The slot overrides it. |
| `variant` | string | `plain` | `plain` or `tile`. |
| `size` | string | `md` | `sm` (14px), `md` (16px), `lg` (20px) or `xl` (24px). |
| `color` | string | `null` | `primary`, `secondary`, `accent`, `neutral`, `success`, `warning`, `error` or `info`. |
| `label` | string | `null` | The accessible name. Without it the icon is hidden from assistive technology. |
| `mirror` | bool | auto | Flips the icon in RTL. Unset, arrows and chevrons (and names registered as directional) mirror. |
| `classes` | array | `[]` | Extra classes per part: `root`, `svg`. |

Every other attribute lands on the root `<span>`.

## Upgrading from 2.x

- `label` used to print visible text beside the icon. It is now the icon's accessible name, and nothing is shown. Put visible text next to the icon yourself.
- Heroicon (`o-home`, `s-home`) and Font Awesome (`fas.user`) names still render through blade-icons, and the `ap.livewireUiComponents.iconAlias` filter still maps them. Prefer the registry's Lucide names: they render the same in Blade, React and Vue.
- The icon no longer defaults to `w-5 h-5`: use `size`. Tailwind size classes on the component still work.
- `id` lands on the root as a plain attribute; `uuid` is gone.

## Accessibility

- Decorative icons are `aria-hidden`. With a `label` the root is `role="img"` with `aria-label`; label any icon that carries meaning on its own, and leave it decorative next to text that says the same thing.
- Plain icons take the surrounding text color, so they meet its contrast. Colored icons use the color's text tone (4.5:1); a tile's foreground meets 3:1 against its fill, and tiles get an edge in high contrast.
- Directional icons mirror in RTL; trends, clocks and external-link icons never do.
