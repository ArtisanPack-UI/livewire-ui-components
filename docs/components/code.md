---
title: Code Component
---

# Code Component

Shows read-only code, built on the ArtisanPack UI design system (`.apui-code`): a block with a header (file name, language and a copy button), optional line numbers and highlighted lines, or inline code in running text. There's no syntax highlighting. For editable code, use [Code Editor](code-editor).

## Basic Usage

```blade
<x-artisanpack-code
	:code="$snippet"
	file-name="format-price.ts"
	language="TypeScript"
	numbered
	:highlight="[ 3 ]"
/>
```

The code is shown as text, never as HTML. Instead of `code`, the content can go in the slot:

```blade
<x-artisanpack-code language="Shell">
composer require artisanpack-ui/livewire-ui-components
</x-artisanpack-code>
```

## Copying

The copy button copies the code without the line numbers, reads "Copied" for two seconds and announces the result, or a failure, to screen readers. It runs on the shared `apuiClipboard` Alpine component, the same one [Clipboard](clipboard) uses. Hide it with `:copyable="false"`; the header goes away when there's no file name, language or copy button either.

## Inline

```blade
<p>
	{{ __( 'Set' ) }} <x-artisanpack-code inline code='data-theme="dark"' /> {{ __( 'on any element to switch that region.' ) }}
</p>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `code` | string | none | The code. The slot replaces it when empty. |
| `language` | string | none | A badge in the header. Only a label: nothing is highlighted. |
| `file-name` | string | none | The file name or a title such as "Terminal". |
| `numbered` | bool | `false` | Shows line numbers. |
| `highlight` | array | `[]` | Line numbers (1-based) to emphasize. Highlight a few lines to make a point. |
| `inline` | bool | `false` | Inline code: a `code` element with no header. |
| `copyable` | bool | `true` | Shows the copy button. |
| `label` | string | the file name, the language, then `__( 'Code' )` | Names the scroll region. |
| `copy-label` | string | `__( 'Copy' )` | The copy button's label. |
| `copied-label` | string | `__( 'Copied' )` | The label after a copy. |
| `copied-message` | string | `__( 'Copied to clipboard' )` | Announced after a copy. |
| `error-message` | string | `__( "Couldn't copy. Select the text and copy it yourself." )` | Announced when the copy fails. |
| `classes` | array | `[]` | Extra classes per part: `root`, `header`, `file`, `language`, `copy`, `status`, `pre`, `line`. |

Every other attribute lands on the root.

## Upgrading from 2.x

- 2.x Code was an Ace editor bound with `wire:model`. Use `<x-artisanpack-code-editor>` for editable code; Code is now read-only and takes `code`.
- `hint`, `light-theme`, `dark-theme`, `light-class`, `dark-class`, `height`, `line-height` and `print-margin` are gone, and are left off the root. `label` now names the scroll region rather than showing a label above the editor.
- `language` no longer defaults to `javascript`, and only shows as a badge.

## Accessibility

- The block is a focusable `role=region` named by `label`, so keyboard users can scroll long lines. Code doesn't wrap.
- Copying is announced in a live region, and so is a failure.
- Line numbers are drawn by CSS, so selecting and copying the code leaves them out.
- The header mirrors in right-to-left layouts; the code stays left to right.
