---
title: Kbd Component
---

# Kbd Component

Shows a keyboard key or shortcut, built on the ArtisanPack UI design system (`.apui-kbd`). Keys are `kbd` elements, and a shortcut is a `kbd` of `kbd` elements, as HTML recommends. `mod` shows ⌘ on macOS and iOS and Ctrl elsewhere, and symbol keys carry their name for screen readers.

## Basic Usage

```blade
<x-artisanpack-kbd :keys="[ 'mod', 'k' ]" />
<x-artisanpack-kbd :keys="[ 'ctrl', 'shift', 'p' ]" />
<x-artisanpack-kbd :keys="[ 'enter' ]" />
<x-artisanpack-kbd>Tab</x-artisanpack-kbd>
```

Without `keys`, the content is one key, as in 2.x.

## Keys

Named keys follow the platform:

| Key | macOS and iOS | Elsewhere |
|---|---|---|
| `mod` | ⌘ | Ctrl |
| `cmd`, `command` | ⌘ | ⌘ |
| `ctrl`, `control` | ⌃ | Ctrl |
| `alt`, `option`, `opt` | ⌥ | Alt |
| `shift` | ⇧ | Shift |
| `backspace` | ⌫ | Backspace |
| `enter`, `return` | ↵ | ↵ |
| `up`, `down`, `left`, `right` | ↑ ↓ ← → | ↑ ↓ ← → |
| `esc`, `tab`, `space`, `delete` | Esc, Tab, Space, Del | the same |

A single character is uppercased; anything else (`F5`, `PgUp`) shows as given. The server can't know the platform, so it renders the "elsewhere" keys and a small Alpine component swaps in Apple's after the page loads. Set `platform="mac"` or `platform="other"` when you know it, such as from the user agent, to render the right keys on the server.

## In running text and menus

Use `md` in a sentence and `sm` in menus, tooltips and other dense rows:

```blade
<p>
	{{ __( 'Press' ) }} <x-artisanpack-kbd :keys="[ 'mod', 'k' ]" /> {{ __( 'to search.' ) }}
</p>

<x-artisanpack-kbd :keys="[ 'mod', 'd' ]" size="sm" />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `keys` | array | `[]` | A shortcut's keys in order. Renders a combo, even for one key. |
| `size` | string | `md` | `sm` or `md`. |
| `platform` | string | `auto` | `auto`, `mac` or `other`. |
| `labels` | array | the translated names | The hidden names of symbol keys, by name: `command`, `control`, `option`, `shift`, `enter`, `tab`, `escape`, `backspace`, `up`, `down`, `left`, `right`. Each defaults through `__()`, such as `__( 'Command' )`. |
| `classes` | array | `[]` | Extra classes per part: `root`, `combo`, `key`. |

Every other attribute lands on the outer `kbd`.

## Upgrading from 2.x

- `<x-artisanpack-kbd>K</x-artisanpack-kbd>` still renders one key, now `.apui-kbd` instead of DaisyUI's `kbd`.
- `id` no longer exists as a prop; it lands on the element like any other attribute.
- For shortcuts, prefer `keys` over several components side by side: it nests the keys and spells them for the platform.

## Accessibility

- Keys are `kbd` elements; a shortcut is a `kbd` of `kbd` elements.
- Symbols (⌘, ⌥, ⇧, ⌃, ↵, arrows) are hidden from screen readers and followed by their name as hidden text.
- A shortcut keeps its key order in right-to-left layouts.
- Kbd isn't a control: never use it as a button.
