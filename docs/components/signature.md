---
title: Signature Component
---

# Signature Component

Captures a signature by drawing, typing or uploading, built on the ArtisanPack UI design system. Every mode produces the same value, a PNG data URL, so your code handles one shape. Type mode is always offered: it's the keyboard-accessible path, and drawing is never the only option.

## Basic Usage

Show who is signing and the date under the pad.

```blade
<x-artisanpack-signature
	wire:model="signature"
	:label="__( 'Signature' )"
	:hint="__( 'Olivia Rhye · Nov 7, 2026' )"
/>
```

## Type first

Start in type mode, or leave upload out with `modes`. Type is always added.

```blade
<x-artisanpack-signature
	wire:model="signature"
	mode="type"
	:modes="[ 'draw', 'type' ]"
	:label="__( 'Signature' )"
	pen-color="primary"
/>
```

## Validation

```blade
{{-- The error comes from $errors for the wire:model name. --}}
<x-artisanpack-signature
	wire:model="signature"
	:label="__( 'Signature' )"
	:hint="__( 'Sign to accept the lease.' )"
	required
/>
```

Validate the value on the server: it's empty, or a `data:image/png;base64,` URL. Check the prefix and the size before you store it.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `mode` | string | `draw` | The mode shown first: `draw`, `type` or `upload`. Emitted as `data-mode`. |
| `modes` | array | all three | The modes offered. `type` is always added. |
| `pen-color` | string | `default` | The ink: `default` (the text color) or a semantic color's text tone. Emitted as `data-pen-color`. |
| `label` | string or slot | none | The visible label. It names the group. |
| `hint` | string or slot | none | Help text, linked with `aria-describedby`. |
| `error` | string | first `$errors` message | Validation message. Marks the field invalid. |
| `invalid` | bool | `false` | Marks the field invalid without a message. |
| `disabled` | bool | `false` | Disables drawing, typing, uploading and the tools. |
| `required` | bool | `false` | Shows the required mark and sets `aria-required` on the typed name. |
| `placeholder` | string | `__( 'Sign above the line' )` | The text under the line. |
| `value` | string | none | The value. `wire:model` binds it instead. |
| `modes-label` | string | `__( 'Signature method' )` | The mode switch group's accessible name. |
| `draw-label` | string | `__( 'Draw' )` | The draw mode button. |
| `type-label` | string | `__( 'Type' )` | The type mode button. |
| `upload-label` | string | `__( 'Upload' )` | The upload mode button. |
| `pad-label` | string | `__( 'Signature pad. Draw with a mouse, finger or stylus, or choose Type.' )` | The canvas's accessible name. |
| `typed-label` | string | `__( 'Type your full name' )` | The typed-name input's accessible name. |
| `upload-trigger-label` | string | `__( 'Choose an image' )` | The upload trigger's text. |
| `undo-label` | string | `__( 'Undo stroke' )` | The icon-only undo button's accessible name. |
| `clear-label` | string | `__( 'Clear' )` | The clear button's text. |
| `cleared-label` | string | `__( 'Signature cleared.' )` | Announced in the live region after clearing. |
| `error-field` | string | the `wire:model` name | The `$errors` key to read. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `required`, `modes`, `mode`, `pad`, `line`, `mark`, `ink`, `typed`, `upload`, `tools`, `undo`, `clear`, `placeholder`, `hint`, `error`. |

`wire:model` binds the value through `$wire.entangle()` (add `.live` to send every change). `class` lands on the root (`.apui-signature`), `name` on a hidden input so plain forms submit the value, and every other attribute on the root. The pad is `wire:ignore`, so a re-render never clears the canvas.

## Accessibility

- The root is a group named by the label and described by the hint and error.
- Type mode is always offered and is the keyboard path; the canvas is an image whose name points to it.
- The mode buttons are toggle buttons with `aria-pressed`; switching modes starts over.
- Undo (icon-only, with a name, mirrored in RTL) and Clear are labeled buttons. Clearing is announced in a polite live region.
- The ink uses text tones, 4.5:1 on the pad in every theme, and `CanvasText` in forced colors.

## Upgrading from 2.x

The DaisyUI signature (built on `signature_pad`) is replaced. `height`, `clear-text`, `hint-class`, `config`, `clear-btn-style`, `error-class`, `omit-error` and `first-error-only` are gone; use `clear-label` for the Clear label and `classes` for styling. The value is still a PNG data URL.
