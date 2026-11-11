---
title: Password Component
---

# Password Component

Password entry with a reveal toggle, an optional strength meter and live requirements, built on the ArtisanPack UI design system. It shares Input's field anatomy. Alpine reveals the value and checks it as people type; the server renders the same state for the initial value.

## Basic Usage

```blade
<x-artisanpack-password wire:model="password" :label="__( 'Password' )" leading-icon="lock" required />
```

The reveal button keeps one name ("Show password") and toggles `aria-pressed`, so screen readers announce the state without a changing label.

## Strength and requirements

On sign-up and reset forms, show the requirements before people type, plus the strength meter, and use `autocomplete="new-password"` so password managers offer to generate one. Each requirement's `pattern` is a regular expression source the value must match.

```blade
<x-artisanpack-password
	wire:model.live="newPassword"
	:label="__( 'New password' )"
	autocomplete="new-password"
	leading-icon="lock"
	strength-meter
	:requirements="[
		[ 'label' => __( 'At least 12 characters' ), 'pattern' => '.{12,}' ],
		[ 'label' => __( 'One number' ), 'pattern' => '\d' ],
		[ 'label' => __( 'One symbol' ), 'pattern' => '[^A-Za-z0-9]' ],
	]"
/>
```

The meter scores the value the same way in every stack: one point each for 8+ and 12+ characters, mixed case, a digit and a symbol, less one, from Weak (1) to Strong (4). It's a guide, not a policy; enforce your rules in validation.

## Validation

```blade
{{-- The error comes from $errors for the wire:model name. --}}
<x-artisanpack-password wire:model="passwordConfirmation" :label="__( 'Confirm password' )" autocomplete="new-password" />
<x-artisanpack-password :label="__( 'Current password' )" :error="__( 'That password is incorrect.' )" />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `size` | `sm` · `md` · `lg` | `md` | Control height. Emitted as `data-size`. |
| `autocomplete` | `current-password` · `new-password` | `current-password` | The control's autocomplete hint. |
| `label` · `hint` | string or slot | none | As Input. |
| `error` | string | first `$errors` message | Validation message. |
| `invalid` · `disabled` · `readonly` · `required` | bool | `false` | As Input. Disabled also disables the reveal button. |
| `placeholder` · `value` | string | none | As Input. |
| `leading-icon` | string | none | Icon name before the value, such as `lock`. The `icon` slot overrides it. |
| `strength-meter` | bool | `false` | Shows the four-segment meter and the level name. |
| `requirements` | array | `[]` | `[ [ 'label' => …, 'pattern' => … ] ]`, checked as people type. |
| `reveal-label` | string | `__( 'Show password' )` | The reveal button's accessible name. |
| `labels` | array | translated | Overrides for `strength`, `weak`, `fair`, `good`, `strong`, `met` and `unmet`. |
| `error-field` | string | the `wire:model` name | The `$errors` key to read. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `required`, `box`, `icon`, `control`, `reveal`, `strength`, `segment`, `strengthLabel`, `requirements`, `requirement`, `hint`, `error`. |

`class` lands on the root (`.apui-password`); every other attribute, `wire:model` included, lands on the native input.

## Accessibility

- The meter is `aria-hidden`; the level name is text in a polite live region, never color alone.
- Each requirement says "(Met)" or "(Not met)" to screen readers, and the list is linked to the control with `aria-describedby`.
- Paste is never blocked.

## Upgrading from 2.x

`icon`, `icon-right`, `right`, `only-password`, `password-icon`, `password-visible-icon`, `clearable`, `prefix`, `suffix`, `prepend`, `append`, `inline`, `hint-class`, `error-class`, `omit-error` and `first-error-only` are gone. Use `leading-icon` for the icon and `classes` for styling.
