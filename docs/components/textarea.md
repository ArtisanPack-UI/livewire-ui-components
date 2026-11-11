---
title: Textarea Component
---

# Textarea Component

Multi-line text entry, built on the ArtisanPack UI design system. It shares Input's field anatomy (label, box, hint, error) and adds an optional character counter. Size it to the expected length of the answer; use [Input](input) for single values.

## Basic Usage

```blade
<x-artisanpack-textarea wire:model="notes" :label="__( 'Notes' )" :hint="__( 'Only your team sees these.' )" rows="3" />
```

## Character counter

`max-length` sets `maxlength` on the control and shows the counter at the end of the box. The counter is hidden from screen readers; a polite live region announces the characters left only once they're within 10% of the limit (at least 10), never on every keystroke. `auto-resize` grows the box with its content.

```blade
<x-artisanpack-textarea
	wire:model.live="description"
	:label="__( 'Description' )"
	:placeholder="__( 'Describe the property, nearby amenities and anything renters should know…' )"
	:max-length="500"
	auto-resize
/>
```

## Validation and states

```blade
{{-- The error comes from $errors for the wire:model name. --}}
<x-artisanpack-textarea wire:model="summary" :label="__( 'Summary' )" required />
<x-artisanpack-textarea :label="__( 'Imported text' )" value="Imported from the March listing feed." readonly />
<x-artisanpack-textarea :label="__( 'Internal notes' )" value="Locked while the listing is reviewed." disabled />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `rows` | int | `4` | Visible lines of text. With `auto-resize`, the minimum. |
| `max-length` | int | none | The most characters allowed. Shows the counter. |
| `auto-resize` | bool | `false` | Grows the box with its content (CSS `field-sizing`). Emitted as `data-auto-resize`. |
| `label` | string or slot | none | The visible label, linked to the control. |
| `hint` | string or slot | none | Help text, linked with `aria-describedby`. |
| `error` | string | first `$errors` message | Validation message. Marks the field invalid. |
| `invalid` | bool | `false` | Marks the field invalid without a message. |
| `disabled` · `readonly` · `required` | bool | `false` | Set on the control; `required` also shows the required mark. |
| `placeholder` | string | none | Never a replacement for the label. |
| `value` | string | none | The value. `wire:model` binds it instead. |
| `remaining-label` | string | `__( 'Characters left: {count}' )` | The counter's announcement near the limit. `{count}` is the characters left. |
| `error-field` | string | the `wire:model` name | The `$errors` key to read. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `required`, `box`, `control`, `count`, `hint`, `error`. |

`class` lands on the root (`.apui-textarea`); every other attribute, `wire:model` and its modifiers included, lands on the native `<textarea>`.

## Accessibility

- The label is linked with `for`/`id`; hint and error with `aria-describedby`. Invalid sets `aria-invalid`.
- The counter is `aria-hidden`; the live region announces "Characters left: 12" only near the limit.
- Disabled and read-only use the sunken surface, never opacity alone.
