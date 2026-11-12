---
title: Clipboard Component
---

# Clipboard Component

Copies a value with clear confirmation, built on the ArtisanPack UI design system (`.apui-clipboard`): a copy button, an icon button with a confirmation tooltip, or a copy field showing the value beside its button. The label reads "Copied" for two seconds and the result is announced to screen readers; a failure is announced too. New in 3.0.

## Basic Usage

```blade
<x-artisanpack-clipboard :value="$listing->shareUrl()" />

<x-artisanpack-clipboard :value="$listing->reference" variant="icon" :label="__( 'Copy reference' )" />
```

## Copy field

Use a copy field for links, keys and IDs. Name each button after what it copies:

```blade
<x-artisanpack-clipboard
	:value="$listing->shareUrl()"
	variant="field"
	size="md"
	:label="__( 'Copy share link' )"
/>
```

## Events

The root dispatches `copy`, with the value as its detail, when the value reached the clipboard, and `error` when copying failed:

```blade
<x-artisanpack-clipboard :value="$url" x-on:copy="$wire.trackShare()" />
```

The copy uses the Clipboard API, with a fallback for pages where it's missing or blocked. The button copies its current value, so a value your Livewire component changes copies the new one.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | string | none | What to copy. The field variant shows it. |
| `timeout` | int | `2000` | How long "Copied" (or the failure) shows, in ms. |
| `variant` | string | `button` | `button`, `icon` or `field`. |
| `size` | string | `sm` | `xs`, `sm` or `md`: the button's size and the field's height. |
| `label` | string | `__( 'Copy' )` | The button's label; the icon button's accessible name. |
| `copied-label` | string | `__( 'Copied' )` | The label after a copy. |
| `copied-message` | string | `__( 'Copied to clipboard' )` | Announced after a copy, and shown in the icon button's tooltip. |
| `error-message` | string | `__( "Couldn't copy. Select the text and copy it yourself." )` | Announced when the copy fails. |
| `classes` | array | `[]` | Extra classes per part: `root`, `button`, `field`, `value`, `tooltip`. |

Every other attribute lands on the root.

## Accessibility

- The result is announced in a `role=status` live region, success or failure: never a silent copy.
- The label changes to "Copied"; the icon button's accessible name does too.
- The value in a copy field reads left to right in every layout and stays selectable, so it can be copied by hand if copying fails.
