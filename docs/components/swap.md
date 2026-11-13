---
title: Swap Component
---

# Swap Component

A two-state toggle that swaps its on and off content, such as a sun and a moon (`.apui-swap`). It is a native checkbox with an accessible name laid over the content, so it works with `wire:model` and needs no script.

## Basic Usage

```blade
<x-artisanpack-swap wire:model.live="darkMode" :label="__( 'Dark mode' )" />
```

`label` names the setting for screen readers ("Dark mode, checkbox, checked"); it isn't shown. By default the swap shows a moon when off and a sun when on.

## Custom content

The `on` and `off` slots replace the icons, and `on-icon` and `off-icon` pick other registry icons. `transition` is `fade` (the default), `rotate` or `none`; reduced motion always swaps at once. `size` is `sm`, `md` or `lg`.

```blade
<x-artisanpack-swap wire:model="menuOpen" :label="__( 'Menu' )" transition="rotate" on-icon="x" off-icon="list" size="lg" />

<x-artisanpack-swap wire:model="starred" :label="__( 'Starred' )" transition="none">
	<x-slot:on><x-artisanpack-icon name="star" color="warning" /></x-slot:on>
	<x-slot:off><x-artisanpack-icon name="star" /></x-slot:off>
</x-artisanpack-swap>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `checked` | bool | `false` | Whether it is on. `wire:model` binds it. |
| `label` | string | `null` | The checkbox's accessible name. Required. |
| `on-icon` | string | `sun` | The icon shown when on. |
| `off-icon` | string | `moon` | The icon shown when off. |
| `transition` | string | `fade` | `fade`, `rotate` or `none`. |
| `size` | string | `md` | `sm`, `md` or `lg`. |
| `disabled` | bool | `false` | Disables the checkbox. |
| `classes` | array | `[]` | Extra classes per part: `root`, `control`, `on`, `off`. |

`class` lands on the root; every other attribute, `wire:model` included, lands on the checkbox.

## Upgrading from 2.x

- The `true` and `false` slots still work; prefer `on` and `off`.
- `true-icon` and `false-icon` still work; prefer `on-icon` and `off-icon`. The 2.x defaults `o-sun` and `o-moon` map to the registry's `sun` and `moon`.
- `icon-size` (Tailwind classes) is ignored: use `size`.
- The `before` and `after` slots are gone. Put content beside the swap yourself.
- The swap now needs a `label`.

## Accessibility

- A native checkbox stays in the DOM over the target, so Space toggles it and screen readers announce its state. The swapping content is `aria-hidden`.
- Name the setting, not the picture: "Dark mode", not "Moon".
- The target is at least 32px square and shows a focus ring while the checkbox has keyboard focus.
- The fade and rotate are removed under reduced motion.
