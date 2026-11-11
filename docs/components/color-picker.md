---
title: ColorPicker Component
---

# ColorPicker Component

A color field built on the ArtisanPack UI design system. The swatch at the start of the field opens a popover with a saturation and brightness area, hue and opacity sliders, a format select (HEX, RGB, HSL, OKLCH), an eyedropper and your preset swatches. The text value stays in the field, always available for typing in any of the four formats. ColorPicker replaces `<x-artisanpack-colorpicker>`, which is now a deprecated alias of this component.

The interaction comes from the design system's behavior engine (`apuiColorPicker`, on Zag's color-picker machine), so load the Alpine adapter before Livewire's script: `@artisanpackScripts` in the layout, which the installer adds (see [Installation](../installation)).

## Basic Usage

```blade
<x-artisanpack-color-picker wire:model="primary" :label="__( 'Primary color' )" />
```

`wire:model` binds the value through `$wire.entangle()`: the text in the current format, such as `#1f74bf`, or `#1f74bfcc` with opacity. Use `wire:model.live` to send every change (including each step of a drag) to the server.

## Formats and swatches

```blade
<x-artisanpack-color-picker
	wire:model="accent"
	format="oklch"
	:label="__( 'Accent color' )"
	:swatches="[ '#1f74bf', '#0b9488', '#1f6f3d', '#de9d16', '#e88761', '#c92f33', '#91256c', '#23272b' ]"
/>
```

`format` sets the starting format; the format select switches it and rewrites the value (`oklch(0.549 0.14 250.2)`), dispatching `format-change`. Typed text in any format is read on Enter or blur. Validate on the server, for example by parsing the value in your component.

Without opacity, every value is opaque and the opacity slider is left out:

```blade
<x-artisanpack-color-picker wire:model="brand" :alpha="false" :label="__( 'Brand color' )" />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `value` | string | none | The color as text in any format. `wire:model` binds it instead. |
| `format` | `hex` · `rgb` · `hsl` · `oklch` | `hex` | The text format. Emitted as `data-format`. |
| `alpha` | bool | `true` | Offers opacity. |
| `swatches` | array | `[]` | Preset colors, shown in the popover. |
| `size` | `sm` · `md` · `lg` | `md` | Control height, matching Input. |
| `label` | string or slot | none | The visible label, for the text value. |
| `hint` | string or slot | none | Help text, linked with `aria-describedby`. |
| `error` | string | `$errors` | Validation message. |
| `invalid` | bool | `false` | Marks the field invalid without a message. |
| `disabled` | bool | `false` | Disables the field and the popover's controls. |
| `required` | bool | `false` | Sets `required` and shows the required mark. |
| `open` | bool | `false` | Whether the popover is open. |
| `portal` | bool | `true` | Renders the popover at the end of the body (`x-teleport`). |
| `trigger-label` | string | `__( 'Choose a color' )` | The swatch button's and the popover's name. |
| `area-label` | string | `__( 'Saturation and brightness' )` | The area's name. |
| `hue-label` | string | `__( 'Hue' )` | The hue slider's name. |
| `alpha-label` | string | `__( 'Opacity' )` | The opacity slider's name. |
| `format-label` | string | `__( 'Format' )` | The format select's name. |
| `eye-dropper-label` | string | `__( 'Pick a color from the screen' )` | The eyedropper's name. |
| `swatches-label` | string | `__( 'Swatches' )` | The preset group's name. |
| `error-field` | string | `wire:model` name | The `$errors` key. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `required`, `box`, `trigger`, `swatch`, `control`, `hint`, `error`, `positioner`, `content`, `area`, `areaBackground`, `areaThumb`, `channelSlider`, `channelSliderTrack`, `channelSliderThumb`, `transparencyGrid`, `row`, `formatSelect`, `eyeDropperTrigger`, `swatchGroup`, `swatchTrigger`. |

## Attributes and events

`class` lands on the root. Listeners (`x-on:*`, `wire:*`) land on the root, where the widget dispatches `value-change`, `open-change` and `format-change` (`$event.detail.format`). Every other attribute (`name`, `autocomplete`) lands on the text input.

## Migrating from colorpicker

The old DaisyUI props (`icon`, `icon-right`, `clearable`, `random`) are gone. The value can now carry opacity; pass `:alpha="false"` to keep six-digit hex.

## Accessibility

- The text value is always visible and editable; the popover is an enhancement.
- The area and sliders are keyboard-operable sliders with `aria-valuetext`; ArrowLeft and ArrowRight follow the text direction under RTL.
- Swatches are buttons named by their value, with `aria-pressed` on the current one.
- The eyedropper only shows where the browser supports picking from the screen.
- Every built-in label is translatable.
