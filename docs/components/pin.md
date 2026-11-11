---
title: Pin Component
---

# Pin Component

A row of single-character cells for one-time codes, built on the ArtisanPack UI design system. One real input with `autocomplete="one-time-code"` lies over the cells, so SMS autofill, paste and screen readers treat the code as one field; the cells mirror its value.

## Basic Usage

```blade
{{-- `complete` fires with the code once every cell is filled. --}}
<x-artisanpack-pin
	wire:model="code"
	wire:complete="verify"
	:label="__( 'Verification code' )"
	:hint="__( 'Enter the 6-digit code we sent to your phone.' )"
	:group-size="3"
/>
```

`wire:model` binds natively. Characters the `type` doesn't allow are dropped as people type or paste, and the clean value is what reaches the server. Once every cell is filled, the input dispatches a `complete` browser event with the code as `$event.detail`: listen with `wire:complete` or `x-on:complete`.

## Masked and alphanumeric

```blade
<x-artisanpack-pin wire:model="pin" :label="__( 'PIN' )" :length="4" mask />
<x-artisanpack-pin wire:model="recoveryCode" :label="__( 'Recovery code' )" :length="8" type="alphanumeric" :group-size="4" />
```

## Validation and sizes

```blade
{{-- The error comes from $errors for the wire:model name. --}}
<x-artisanpack-pin wire:model="code" :label="__( 'Verification code' )" :group-size="3" />
<x-artisanpack-pin :label="__( 'Small' )" size="sm" :group-size="3" />
<x-artisanpack-pin :label="__( 'Disabled' )" value="503186" :group-size="3" disabled />
```

Say why a code failed; don't clear it without telling people.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `length` | int | `6` | The number of cells. Sets `maxlength`. |
| `type` | `numeric` · `alphanumeric` | `numeric` | The characters allowed. Numeric sets `inputmode="numeric"`. |
| `mask` | bool | `false` | Shows a dot per character and makes the control a password input. |
| `group-size` | int | none | Draws a separator after every `group-size` cells. |
| `size` | `sm` · `md` | `md` | Cell size. Emitted as `data-size`. |
| `label` · `hint` | string or slot | none | As Input. |
| `error` | string | first `$errors` message | Validation message. |
| `invalid` · `disabled` · `required` | bool | `false` | As Input. |
| `value` | string | none | The code. `wire:model` binds it instead. |
| `error-field` | string | the `wire:model` name | The `$errors` key to read. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `required`, `cells`, `control`, `cell`, `separator`, `hint`, `error`. |

## Upgrading from 2.x

- `size` (the number of cells) is now `length`, and `size` is the cell size.
- `numeric` is `type="numeric"` (the default); `hide` and `hide-type` are `mask`.
- The `completed` and `incomplete` events are replaced by `complete` (present tense, as in every stack). Use `wire:model` or `x-on:input` to follow partial codes.
