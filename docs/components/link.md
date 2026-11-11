---
title: Link Component
---

# Link Component

Navigates somewhere, built on the ArtisanPack UI design system (`.apui-link`). Links in running text stay underlined, so color isn't the only cue; standalone links drop the underline until hover and take icons. Without an `href`, Link renders a `<button type="button">` with the same styles, for an action that reads as a link.

## Basic Usage

```blade
<p>
	{{ __( 'Read the' ) }} <x-artisanpack-link href="/terms">{{ __( 'listing terms' ) }}</x-artisanpack-link>
	{{ __( 'or' ) }} <x-artisanpack-link href="/privacy" tone="muted">{{ __( 'privacy policy' ) }}</x-artisanpack-link>.
</p>
```

## Standalone links and icons

```blade
<x-artisanpack-link :href="route( 'properties.index' )" standalone trailing-icon="arrow-right">{{ __( 'View all properties' ) }}</x-artisanpack-link>
<x-artisanpack-link href="/brochure.pdf" standalone leading-icon="download">{{ __( 'Download PDF' ) }}</x-artisanpack-link>
```

Arrow and chevron icons mirror in right-to-left layouts. The `icon` and `iconEnd` slots override the icon names.

## External links

`external` opens the link in a new tab (`target="_blank"` with `rel="noopener noreferrer"`), adds the external-link icon and tells screen readers in visually hidden text. The text defaults to `__( '(opens in a new tab)' )`; `external-label` changes it.

```blade
<x-artisanpack-link href="https://docs.artisanpackui.dev" standalone external>{{ __( 'Documentation' ) }}</x-artisanpack-link>
```

## Actions

A link without `href` is a button, so `wire:click` works from the keyboard and screen readers announce it as a button:

```blade
<x-artisanpack-link wire:click="logout">{{ __( 'Log out' ) }}</x-artisanpack-link>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `href` | string | none | The destination. Only relative, http, https, mailto, tel and sms URLs are used; without one, a button renders. |
| `tone` | `primary` · `muted` | `primary` | `muted` for secondary links. Emitted as `data-tone`. |
| `standalone` | bool | `false` | A link on its own line: medium weight, underlined on hover. Emitted as `data-standalone`. |
| `external` | bool | `false` | Opens a new tab, adds the external-link icon and announces it. |
| `external-label` | string | `__( '(opens in a new tab)' )` | The visually hidden text an external link announces. |
| `leading-icon` | string | none | An icon name before the text. The `icon` slot overrides it. |
| `trailing-icon` | string | none | An icon name after the text; it replaces the external-link icon. The `iconEnd` slot overrides it. |
| `navigate` | bool | `true` | Adds `wire:navigate` to an internal link, so Livewire swaps the page in without a full reload. Off-site, `#`, `mailto:` and `external` links load normally. |
| `classes` | array | `[]` | Extra classes per part: `root`, `icon`, `iconEnd`. |

Every other attribute, `wire:click` included, lands on the root.

## Upgrading from 2.x

- `color`, `underline` and `hover-underline` are gone: use `tone` and `standalone`, or tokens and `classes`.
- `icon` and `icon-right` are `leading-icon` and `trailing-icon`.
- Internal links still add `wire:navigate` by default. `no-wire-navigate` still turns it off but is deprecated: use `:navigate="false"`.
- The tooltip props are gone; wrap the link in a Tooltip.

## Accessibility

- A native anchor, or a native button without `href`.
- Inline links stay underlined (WCAG 1.4.1); standalone links are set apart by position and weight.
- External links say they open a new tab. Icons are `aria-hidden`; an icon-only link needs an `aria-label`.
- Write link text that says where it goes, never "click here".
