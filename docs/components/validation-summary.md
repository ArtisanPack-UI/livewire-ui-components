---
title: ValidationSummary Component
---

# ValidationSummary Component

Lists every error in a form after a failed submit, each linked to its field, built on the ArtisanPack UI design system. It reads Laravel's error bag, renders nothing when there are no errors, and replaces the deprecated [Errors](errors) component. It isn't an error state for a failed page: that's ErrorState.

## Basic Usage

```blade
{{-- Lists the first message of every key in $errors, linked to its field. --}}
<x-artisanpack-validation-summary />

{{-- Or only some keys, in order, with your own title. --}}
<x-artisanpack-validation-summary :only="[ 'postalCode', 'photos' ]" :title="__( 'Fix these before publishing' )" />
```

Put it at the top of the form. When it appears it's an `alert` and takes focus. Each link points to `#field` and moves focus to the element with that id, else the control with that `name` or `wire:model` (the design-system fields put `wire:model` on the native control, so the key is enough). Fields keep their own inline errors; use the same wording.

## Warnings

Non-blocking recommendations use `color="warning"` and `role="status"`, and don't take focus. Pass them with `errors`, as a list of `field` and `message`, or a map of field to message(s):

```blade
<x-artisanpack-validation-summary
	color="warning"
	:errors="[
		[ 'field' => 'description', 'message' => __( 'Description is shorter than 80 characters.' ) ],
		[ 'field' => 'floor-plan', 'message' => __( 'No floor plan uploaded.' ) ],
	]"
/>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `errors` | array | `$errors` | `[ [ 'field' => …, 'message' => … ] ]` or `[ field => message(s) ]`. Wins over the error bag. |
| `only` | array | every key | The fields to list, in order. |
| `color` | `error` · `warning` | `error` | Emitted as `data-color`. |
| `title` | string or slot | translated count | Defaults to "Fix 1 field" / "Fix :count fields", or ":count recommendations" for warnings. |
| `classes` | array | `[]` | Extra classes per part: `root`, `icon`, `title`, `list`, `item`, `link`. |

## Notes

- It takes focus when it first renders. Livewire morphs it on later failed submits, so it doesn't take focus again while it stays on the page.
- Don't show it before the first submit, and don't use it with live (`wire:model.live`) validation.
