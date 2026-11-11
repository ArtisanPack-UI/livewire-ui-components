---
title: Toggle Component
---

# Toggle Component

A switch for settings that take effect immediately, built on the ArtisanPack UI design system: a native checkbox with `role="switch"` over a drawn track. In forms that need a submit, use [Checkbox](checkbox).

## Basic Usage

```blade
<x-artisanpack-toggle wire:model.live="darkMode" :label="__( 'Dark mode' )" />
```

Label the setting, not the state ("Dark mode", not "On"). `wire:model` binds the checked state natively; the track follows a click before the round trip, and `data-state` follows on change.

## Settings rows

```blade
<x-artisanpack-toggle wire:model.live="digest" :label="__( 'Weekly revenue digest' )" :description="__( 'Every Monday at 9:00.' )" />
<x-artisanpack-toggle :label="__( 'Two-factor authentication' )" :description="__( 'Required by your workspace admin.' )" checked disabled />
```

## Sizes and colors

```blade
<x-artisanpack-toggle :label="__( 'Small' )" size="sm" checked />
<x-artisanpack-toggle :label="__( 'Medium' )" checked />
<x-artisanpack-toggle :label="__( 'Large' )" size="lg" color="success" checked />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `checked` | bool | `false` | Whether it's on. `wire:model` binds it instead. Emitted as `data-state`. |
| `size` | `sm` · `md` · `lg` | `md` | Track size. Emitted as `data-size`. |
| `color` | semantic color | `primary` | The track color when on. Emitted as `data-color`. |
| `label` · `description` | string or slot | none | The label, and supporting text linked with `aria-describedby`. |
| `error` | string | first `$errors` message | Validation message. |
| `invalid` · `disabled` · `required` | bool | `false` | Set on the control. |
| `error-field` | string | the `wire:model` name | The `$errors` key to read. |
| `classes` | array | `[]` | Extra classes per part: `root`, `track`, `control`, `thumb`, `label`, `description`, `error`. |

## Accessibility

- Off keeps a 3:1 outline so the control is visible without color. The thumb moves to the logical end, so it mirrors under RTL, and its motion is removed under reduced motion.

## Upgrading from 2.x

`right`, `hint`, `hint-class`, `error-class`, `omit-error` and `first-error-only` are gone. `hint` is `description`.
