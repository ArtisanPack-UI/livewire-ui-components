---
title: Avatar Component
---

# Avatar Component

A person or organization as an image, initials or an icon, built on the ArtisanPack UI design system (`.apui-avatar`). Without an image the avatar shows the initials of `name`, in a color picked from a stable hash of the name; without a name, a user icon.

## Basic Usage

```blade
<x-artisanpack-avatar name="Olivia Rhye" />
<x-artisanpack-avatar name="Phoenix Baker" :src="$user->avatar_url" size="lg" />
<x-artisanpack-avatar name="Apexify" shape="square" color="neutral" />
```

The image sits over the initials, so a broken image falls back to them. Only relative, http, https and `data:image` sources are used.

## Status

```blade
<x-artisanpack-avatar name="Olivia Rhye" status="online" />
<x-artisanpack-avatar name="Lana Steiner" status="away" />
<x-artisanpack-avatar name="Demi Wilkinson" status="offline" />
```

The status is a dot and is also announced in words: "Olivia Rhye (Online)". The words default to `__( 'Online' )`, `__( 'Away' )` and `__( 'Offline' )`; `status-label` overrides them.

## Group

```blade
<x-artisanpack-avatar-group :avatars="$members" :max="4" size="sm" :label="__( 'Project members' )" />
```

`avatars` takes `{ name, src, alt, initials, icon, color, status }`; past `max` the rest collapse into a +N avatar named by `more-label` (`__( '{count} more' )`). Avatar children render after the data.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `name` | string | none | The full name: initials (first and last word), accessible name and hashed color. |
| `src` | string | none | The image URL. |
| `alt` | string | none | The accessible name when it should differ from `name`. |
| `initials` | string | none | Overrides the initials, such as `+4`. |
| `icon` | string | none | An icon name shown instead of initials. |
| `size` | `xs` · `sm` · `md` · `lg` · `xl` | `md` | The control heights: 24, 28, 36, 44 or 52px. Emitted as `data-size`. |
| `shape` | `circle` · `square` | `circle` | Square for organizations and workspaces. Emitted as `data-shape`. |
| `color` | one of the eight semantic colors | hashed from `name` | The initials' tone. Emitted as `data-color`. |
| `status` | `online` · `away` · `offline` | none | The presence dot. |
| `status-label` | string | `__( 'Online' )` … | The status in words, added to the accessible name. |
| `classes` | array | `[]` | Extra classes per part: `root`, `image`, `initials`, `status`. |

## Upgrading from 2.x

- `image` is `src` and `placeholder` is `name` (the initials now come from it). Both 2.x attributes still work, deprecated.
- `title` and `subtitle` are gone: lay out a name beside an avatar with a ListItem, or use the UserMenu.
- `color` takes the eight semantic names only, and `color-adjustment` is gone.

## Accessibility

- The root is `role="img"` named by `alt` or `name` plus the status; everything inside is decorative.
- Without a name or `alt` the avatar is decorative (`aria-hidden`).
- The status is announced in words, never by color alone.
